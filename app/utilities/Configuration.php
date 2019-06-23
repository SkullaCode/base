<?php


namespace App\Utility;


use Psr\Container\ContainerInterface;

class Configuration
{
    /**
     * @var string
     */
    public $RootDirectory;

    /**
     * @var string
     */
    public $ErrorPageDirectory;

    /**
     * @var string
     */
    public $StorageDirectory;

    /**
     * @var string
     */
    public $TemplateDirectory;

    /**
     * @var string
     */
    public $PreLoader;

    /**
     * @var string
     */
    public $Mode;

    /**
     * @var string
     */
    public $DatabaseConfig;

    /**
     * @var string
     */
    public $EmailConfig;

    /**
     * @var string
     */
    public $CompanyConfig;

    /**
     * @var string
     */
    public $ContextDirectory;

    /**
     * @var int
     */
    public $LocalCountryID;

    /**
     * @var bool
     */
    public $ShowErrorDetailed;

    /**
     * @var bool
     */
    public $ShowContentLengthHeader;

    /**
     * @var string
     */
    public $AppUserID;

    /**
     * @var string
     */
    public $DefaultAppUser;

    public function __construct(ContainerInterface $c)
    {
        $settings = $c->get('settings');
        $this->Mode = $settings['config']['mode'];
        $this->CompanyConfig = $settings['company'];
        $this->ContextDirectory = $settings['config'][$this->Mode]['context_directory'];
        $this->DatabaseConfig = $settings['config'][$this->Mode]['database'];
        $this->EmailConfig = $settings['email'];
        $this->ErrorPageDirectory = $settings['config']['error_page_directory'];
        $this->PreLoader = $settings['config']['pre_loader'];
        $this->RootDirectory = $settings['config']['root_directory'];
        $this->StorageDirectory = $settings['config']['storage_directory'];
        $this->TemplateDirectory = $settings['config']['template_path'];
        $this->LocalCountryID = $settings['local_state_id'];
        $this->ShowContentLengthHeader = $settings['addContentLengthHeader'];
        $this->ShowErrorDetailed = $settings['displayErrorDetails'];
        $this->AppUserID = $settings['config']['app_user_id'];
        $this->DefaultAppUser = $settings['config']['default_app_user'];
    }
}