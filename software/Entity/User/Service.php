<?php


namespace Software\Entity\User;


use App\Constant\ErrorCode;
use App\Constant\ErrorModel;
use App\Constant\RequestModel;
use App\Controller\CrudController;
use App\Extension\Extensions;
use Delight\Auth\AttemptCancelledException;
use Delight\Auth\Auth;
use Delight\Auth\AuthError;
use Delight\Auth\ConfirmationRequestNotFound;
use Delight\Auth\DuplicateUsernameException;
use Delight\Auth\EmailNotVerifiedException;
use Delight\Auth\InvalidEmailException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\InvalidSelectorTokenPairException;
use Delight\Auth\NotLoggedInException;
use Delight\Auth\ResetDisabledException;
use Delight\Auth\TokenExpiredException;
use Delight\Auth\TooManyRequestsException;
use Delight\Auth\UnknownIdException;
use Delight\Auth\UserAlreadyExistsException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;
use Software\EntityContext\File;
use Software\Entity\User\Constants\PrivilegeCode;
use Software\Entity\User\Constants\Status;

class Service extends CrudController
{
    /**
     * @var Auth
     */
    private $authenticator;

    /**
     * @var File
     */
    private $file_context;

    public function __construct(ContainerInterface $c)
    {
        parent::__construct($c);
        $this->entityType = 'User';
        $this->model = Model::class;
        $this->dbContext = $this->Context->User;
        $this->authenticator = $c->get("Authenticator");
        $this->file_context = new File($c);
    }

    public function Register(Request $rq)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $rq = $rq
            ->withAttribute(ErrorModel::ERROR_LOCATION,'User')
            ->withAttribute(ErrorModel::ERROR_ENTITY,'Register');
        try
        {
            $req = $this->Utility->Request;
            $mailer = $this->Utility->Email;
            $userId = $this->authenticator->register(
                $vm->Email,
                $vm->Password,
                $vm->Username,
                function($selector,$token) use($vm,$req,$mailer){
                    $mailer->LoadTemplate('new_account',[
                        'reset_link'    =>  $req->BaseURL() . 'user/auth/verify?selector=' . urlencode($selector) . '&token=' . urlencode($token),
                        'email'         =>  $vm->Email,
                        'user_name'     =>  $vm->Username
                    ]);
                    $mailer->AddSubject('New Account Registration');
                    $mailer->AddRecipient($vm->Email);
                    $mailer->SendMail();
                });
            $vm->UserID = $userId;
            $rq = $rq->withAttribute(RequestModel::VIEW_MODEL,$vm);
            $this->FireHook("RegisterUser",$rq);
            return Extensions::SuccessHandler($rq,"User registered successfully");
        }
        catch (AuthError $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::ACCOUNT_CREATION_FALIED);
            return Extensions::ErrorHandler($rq);
        }
        catch (InvalidEmailException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_EMAIL);
            return Extensions::ErrorHandler($rq);
        }
        catch (InvalidPasswordException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_PASSWORD);
            return Extensions::ErrorHandler($rq);
        }
        catch (TooManyRequestsException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::TOO_MANY_REQUESTS);
            return Extensions::ErrorHandler($rq);
        }
        catch (UserAlreadyExistsException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::ACCOUNT_NOT_UNIQUE);
            return Extensions::ErrorHandler($rq);
        }
    }

    public function Validate(Request $rq)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $rq = $rq
            ->withAttribute(ErrorModel::ERROR_LOCATION,"User")
            ->withAttribute(ErrorModel::ERROR_ENTITY,"Validate");
        try
        {
            $this->authenticator->login($vm->Username,$vm->Password);
            $hash = mktime();
            $token = md5($this->Utility->Request->UserAgent().$hash);
            $session = $this->Utility->Session->GetItem(RequestModel::SESSION_LOAD);
            $session['hash'] = $hash;
            $loadedSession[$this->Environment->AppUserID] = $this->authenticator->getUserId();
            $this->Utility->Session->SetItem(RequestModel::SESSION_LOAD,$session);
            $rq = $rq->withAttribute(RequestModel::PROCESSED_MODEL,[
                'email'         => $this->authenticator->getEmail(),
                'token'         =>  $token
            ]);
            return Extensions::SuccessHandler($rq,"Login Successful");
        }
        catch (AttemptCancelledException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_CREDENTIALS);
            return Extensions::ErrorHandler($rq);
        }
        catch (AuthError $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::ACCOUNT_VALIDATION_FAILED);
            return Extensions::ErrorHandler($rq);
        }
        catch (EmailNotVerifiedException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::ACCOUNT_NOT_VERIFIED);
            return Extensions::ErrorHandler($rq);
        }
        catch (InvalidEmailException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_USERNAME);
            return Extensions::ErrorHandler($rq);
        }
        catch (InvalidPasswordException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_PASSWORD);
            return Extensions::ErrorHandler($rq);
        }
        catch (TooManyRequestsException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::TOO_MANY_REQUESTS);
            return Extensions::ErrorHandler($rq);
        }
    }

    public function ChangePassword(Request $rq)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $rq = $rq
            ->withAttribute(ErrorModel::ERROR_LOCATION,"User")
            ->withAttribute(ErrorModel::ERROR_ENTITY,"ChangePassword");
        try
        {
            $this->authenticator->resetPassword($vm->Selector,$vm->Token,$vm->Password);
            return Extensions::SuccessHandler($rq,"Password Change Successful");
        }
        catch (AuthError $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::ACCOUNT_NOT_RESET);
            return Extensions::ErrorHandler($rq);
        }
        catch (InvalidPasswordException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_PASSWORD);
            return Extensions::ErrorHandler($rq);
        }
        catch (InvalidSelectorTokenPairException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_CREDENTIALS);
            return Extensions::ErrorHandler($rq);
        }
        catch (ResetDisabledException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::ACCOUNT_RESET_DISABLED);
            return Extensions::ErrorHandler($rq);
        }
        catch (TokenExpiredException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::TOKEN_EXPIRED);
            return Extensions::ErrorHandler($rq);
        }
        catch (TooManyRequestsException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::TOO_MANY_REQUESTS);
            return Extensions::ErrorHandler($rq);
        }
    }

    public function ResetAccount(Request $rq)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $rq = $rq
            ->withAttribute(ErrorModel::ERROR_LOCATION,"User")
            ->withAttribute(ErrorModel::ERROR_ENTITY,"ResetAccount");

        try
        {
            $mailer = $this->Utility->Email;
            $request = $this->Utility->Request;
            $this->authenticator->forgotPassword($vm->Email, function ($selector, $token) use ($vm,$mailer,$request) {
                $mailer->LoadTemplate('account_recovery', [
                    'reset_link'    =>  $request->BaseURL() . 'user/auth/recover?selector=' . urlencode($selector) . '&token=' . urlencode($token),
                    'email'         =>  $vm->Email
                ]);
                $mailer->AddSubject('Account Recovery');
                $mailer->AddRecipient($vm->Email);
                $mailer->SendMail();
            });
            $this->Utility->Session->Destroy();
            return Extensions::SuccessHandler($rq,"Account Reset Successful");
        }
        catch (AuthError $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::ACCOUNT_NOT_RESET);
            return Extensions::ErrorHandler($rq);
        }
        catch (EmailNotVerifiedException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::ACCOUNT_NOT_VERIFIED);
            return Extensions::ErrorHandler($rq);
        }
        catch (InvalidEmailException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_USERNAME)->withAttribute('Error_Entity',"Email Address");
            return Extensions::ErrorHandler($rq);
        }
        catch (ResetDisabledException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::ACCOUNT_RESET_DISABLED);
            return Extensions::ErrorHandler($rq);
        }
        catch (TooManyRequestsException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::TOO_MANY_REQUESTS);
            return Extensions::ErrorHandler($rq);
        }
    }

    public function ResendResetAccount(Request $rq)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $rq = $rq
            ->withAttribute(ErrorModel::ERROR_LOCATION,"User")
            ->withAttribute(ErrorModel::ERROR_ENTITY,"ResetAccount");

        try
        {
            $mailer = $this->Utility->Email;
            $request = $this->Utility->Request;
            $this->authenticator->resendConfirmationForEmail($vm->Email, function ($selector, $token) use ($vm,$mailer,$request) {
                $mailer->LoadTemplate('account_recovery', [
                    'reset_link'    =>  $request->BaseURL() . 'user/auth/recover?selector=' . urlencode($selector) . '&token=' . urlencode($token),
                    'email'         =>  $vm->Email
                ]);
                $mailer->AddSubject('Account Recovery');
                $mailer->AddRecipient($vm->Email);
                $mailer->SendMail();
            });
            $this->Utility->Session->Destroy();
            return Extensions::SuccessHandler($rq,"Account Reset Resent Successful");
        }
        catch (ConfirmationRequestNotFound $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::NOT_FOUND);
            return Extensions::ErrorHandler($rq);
        }
        catch (TooManyRequestsException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::TOO_MANY_REQUESTS);
            return Extensions::ErrorHandler($rq);
        }
    }

    public function ConfirmEmail(Request $rq)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        try
        {
            $this->authenticator->canResetPasswordOrThrow($vm->Selector,$vm->Token);
            $session = $this->Utility->Session;
            $session->SetItem(RequestModel::SESSION_LOAD,[]);
            $session->SetFlashItem("selector",$vm->Selector);
            $session->SetFlashItem("token",$vm->Token);
            return Extensions::RedirectHandler($rq,$this->Utility->Request->BaseURL("change-password"));
        }
        catch (AuthError $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::ACCOUNT_VALIDATION_FAILED);
            return Extensions::ErrorHandler($rq);
        }
        catch (InvalidSelectorTokenPairException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_CREDENTIALS);
            return Extensions::ErrorHandler($rq);
        }
        catch (TokenExpiredException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_CREDENTIALS);
            return Extensions::ErrorHandler($rq);
        }
        catch (TooManyRequestsException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::TOO_MANY_REQUESTS);
            return Extensions::ErrorHandler($rq);
        }
        catch (ResetDisabledException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::ACCOUNT_VALIDATION_FAILED);
            return Extensions::ErrorHandler($rq);
        }
    }

    public function VerifyAccount(Request $rq)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $rq = $rq
            ->withAttribute(ErrorModel::ERROR_LOCATION,'User')
            ->withAttribute(ErrorModel::ERROR_ENTITY,'VerifyAccount');
        try
        {
            $this->authenticator->confirmEmail($vm->Selector,$vm->Token);
            return Extensions::RedirectHandler($rq,$this->Utility->Request->BaseURL("login"));
        }
        catch (AuthError $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::ACCOUNT_VALIDATION_FAILED);
            return Extensions::ErrorHandler($rq);
        }
        catch (InvalidSelectorTokenPairException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_CREDENTIALS);
            return Extensions::ErrorHandler($rq);
        }
        catch (TokenExpiredException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_CREDENTIALS);
            return Extensions::ErrorHandler($rq);
        }
        catch (TooManyRequestsException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::TOO_MANY_REQUESTS);
            return Extensions::ErrorHandler($rq);
        }
        catch (UserAlreadyExistsException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::ACCOUNT_NOT_UNIQUE);
            return Extensions::ErrorHandler($rq);
        }
    }

    public function AddRole(Request $rq)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        try
        {
            $this->authenticator->admin()->addRoleForUserById($vm->User->ID,$vm->role);
            return Extensions::SuccessHandler($rq,"Role added successfully");
        } catch (UnknownIdException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_CREDENTIALS);
            return Extensions::ErrorHandler($rq);
        }
    }

    public function RemoveRole(Request $rq)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        try
        {
            $this->authenticator->admin()->removeRoleForUserById($vm->User->ID,$vm->role);
            return Extensions::SuccessHandler($rq,"Role removed successfully");
        } catch (UnknownIdException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_CREDENTIALS);
            return Extensions::ErrorHandler($rq);
        }
    }

    public function Disable(Request $rq)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        /**
         * @var Model $user
         */
        $user = $vm->User;
        $user->Status = Status::LOCKED;
        $this->Context->User->Update($user);
        return Extensions::SuccessHandler($rq,'user account disabled');
    }

    public function Ban(Request $rq)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        /**
         * @var Model $user
         */
        $user = $vm->User;
        $user->Status = Status::BANNED;
        $this->Context->User->Update($user);
        return Extensions::SuccessHandler($rq,'user account disabled');
    }

    public function Enable(Request $rq)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        /**
         * @var Model $user
         */
        $user = $vm->User;
        $user->Status = Status::ACTIVE;
        $this->Context->User->Update($user);
        return Extensions::SuccessHandler($rq,'user account enabled');
    }

    public function UploadProfilePic(Request $rq)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        /**
         * @var UploadedFileInterface $uploadedFile
         */
        $uploadedFile = $vm->ProfilePicture;
        $file = $vm->File;
        $date = $this->Utility->DateTime;
        if(!$file)
        {
            $fileName = $uploadedFile->getClientFilename();
            $x = explode('.',$fileName);
            $ext = (isset($x[1])) ? $x[1] : null;
            $file = (object)[
                'ID'            =>  $vm->ProfilePictureFileName,
                'MIME'          =>  $uploadedFile->getClientMediaType(),
                'Extension'     =>  $ext,
                'Name'          =>  $fileName,
                'Content'       =>  $uploadedFile->getStream()->getContents(),
                'Size'          =>  $uploadedFile->getStream()->getSize(),
                'cts'           =>  $date->FormatTimeStampToString(time(),$date->DateTimeStringFormat()),
                'mts'           =>  $date->FormatTimeStampToString(time(),$date->DateTimeStringFormat())
            ];
            $res = $this->file_context->Add($file);
        }
        else
        {
            $fileName = $uploadedFile->getClientFilename();
            $x = explode('.',$fileName);
            $ext = (isset($x[1])) ? $x[1] : null;
            $file->MIME         = $uploadedFile->getClientMediaType();
            $file->Extension    = $ext;
            $file->Name         = $fileName;
            $file->Content      = $uploadedFile->getStream()->getContents();
            $file->Size         = $uploadedFile->getStream()->getSize();
            $file->mts          = $date->FormatTimeStampToString(time(),$date->DateTimeStringFormat());
            $res = $this->file_context->UpdateFile($file);
        }
        if(is_null($res))
        {
            $rq = $rq
                ->withAttribute('Error_Location','UserService')
                ->withAttribute('Error_Entity','ProfilePicture')
                ->withAttribute('Error_Code','Profile picture was not saved');
            return Extensions::ErrorHandler($rq);
        }
        $this->Message = "Profile picture upload successful";
        return Extensions::SuccessHandler($rq,$this->Message);
    }

    public function UpdatePassword(Request $rq)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        try
        {
            $this->authenticator->changePassword($vm->OldPassword, $vm->NewPassword);
            return Extensions::SuccessHandler($rq,"Password change successfully");
        }
        catch (AuthError $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_CREDENTIALS);
            return Extensions::ErrorHandler($rq);
        }
        catch (InvalidPasswordException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_PASSWORD);
            return Extensions::ErrorHandler($rq);
        }
        catch (NotLoggedInException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::REQUIRED_FIELD_MISSING);
            return Extensions::ErrorHandler($rq);
        }
        catch (TooManyRequestsException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::TOO_MANY_REQUESTS);
            return Extensions::ErrorHandler($rq);
        }
    }

    public function Create(Request $rq)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $rq = $rq
            ->withAttribute(ErrorModel::ERROR_LOCATION,'User')
            ->withAttribute(ErrorModel::ERROR_ENTITY,'Create');
        try
        {
            $userId = $this->authenticator->admin()->createUserWithUniqueUsername(
                $vm->Email,
                $vm->Password,
                $vm->Username);
            $vm->UserID = $userId;
            $rq = $rq->withAttribute(RequestModel::VIEW_MODEL,$vm);
            $this->FireHook("CreateUser",$rq);
            return Extensions::SuccessHandler($rq,"User created successfully");
        } catch (AuthError $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::ACCOUNT_VALIDATION_FAILED);
            return Extensions::ErrorHandler($rq);
        }
        catch (DuplicateUsernameException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::DUPLICATE_FIELD);
            return Extensions::ErrorHandler($rq);
        }
        catch (InvalidEmailException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_EMAIL);
            return Extensions::ErrorHandler($rq);
        }
        catch (InvalidPasswordException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::INVALID_PASSWORD);
            return Extensions::ErrorHandler($rq);
        }
        catch (UserAlreadyExistsException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::ACCOUNT_NOT_UNIQUE);
            return Extensions::ErrorHandler($rq);
        }
    }

    public function Update(Request $rq)
    {
        $this->FireHook("UpdateUserProfile",$rq);
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        if($vm->ProfileUpdateSuccessful)
            return Extensions::SuccessHandler($rq,"user profile updated successfully");
        return Extensions::ErrorHandler($rq);
    }

    public function Delete(Request $rq, Response $rs, $args)
    {
        try
        {
            $this->authenticator->admin()->deleteUserById($args['id']);
            return Extensions::SuccessHandler($rq,"Account deleted successfully");
        }
        catch (AuthError $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::ACCOUNT_VALIDATION_FAILED);
            return Extensions::ErrorHandler($rq);
        }
        catch (UnknownIdException $e)
        {
            $rq = $rq->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::NOT_FOUND);
            return Extensions::ErrorHandler($rq);
        }
    }

    protected function ViewModelMapping($model, $viewModel, $mappingType, $request)
    {
        $date = $this->Utility->DateTime;
        $viewModel = parent::ViewModelMapping($model,$viewModel,$mappingType,$request);
        $roles = [];
        $roleKeys = [];
        try {
            $r = $this->authenticator->admin()->getRolesForUserById($viewModel->ID);
            foreach($r as $key => $val)
            {
                if((int)$key !== PrivilegeCode::DEVELOPER)
                    $roles[] = PrivilegeCode::ToString($key);
                $roleKeys[] = $key;
            }
            $viewModel->Roles = $roles;
            $viewModel->RoleKeys = $roleKeys;
        } catch (UnknownIdException $e) {

        }
        $viewModel->Registered = $date->FormatDateTimeToUIString($viewModel->Registered);
        $viewModel->LastLogin = (!is_null($viewModel->LastLogin))
            ? $date->FormatDateTimeToUIString($viewModel->LastLogin)
            : "Never";
        return $viewModel;
    }
}