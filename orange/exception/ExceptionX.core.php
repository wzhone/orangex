<?php
namespace core\exception;
class ExceptionX extends \Exception{


    public function __construct (string $message = '' , int $code = 0 , Throwable $previous = NULL){
        parent::__construct(i18n($message),$code,$previous);
    }

}