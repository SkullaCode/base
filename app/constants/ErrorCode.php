<?php


namespace App\Constant;


class ErrorCode
{
    const INVALID_USERNAME              = "The username seems to be invalid";
    const INVALID_PASSWORD              = "The password seems to be invalid";
    const INVALID_EMAIL                 = "The email address seems to be invalid";
    const INVALID_FIELD                 = "The selected field seems to be invalid";
    const INVALID_STATUS                = "The associated status seems to be invalid";
    const PASSWORD_MISMATCH             = "The entered passwords don't seem to match";
    const REQUIRED_FIELD_MISSING        = "There seems to be a required field missing";
    const INVALID_CREDENTIALS           = "The credentials provided seem to be invalid";
    const ACCOUNT_NOT_VERIFIED          = "This account is not verified";
    const ACCOUNT_NOT_UNIQUE            = "The account credentials entered are not unique";
    const ACCOUNT_NOT_RESET             = "This account has not been reset";
    const ACCOUNT_NOT_ADMIN             = "This account does not have admin privileges";
    const ACCOUNT_RESET_DISABLED        = "Resetting this account has been disabled";
    const ACCOUNT_VALIDATION_FAILED     = "Validation for this account has failed";
    const TOO_MANY_REQUESTS             = "There are too many requests to the server";
    const TOKEN_EXPIRED                 = "The token has expired";
    const DUPLICATE_FIELD               = "There seems to be a duplicate field";
    const PRIMARY_FIELD                 = "The field selected is a primary field";
    const PRIMARY_FIELD_MISSING         = "A primary field is missing";
    const INTERNAL_SERVER_ERROR         = "Something went wrong on the server";
}