<?php

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2017/9/22
 * Time: 11:45
 */

namespace Backend\Exception;

use Backend\helpers\Helpers;
use Backend\helpers\Lang;
use yii\web\UnauthorizedHttpException;

class ErrorHandler extends \yii\base\ErrorHandler
{

    public function renderException($exception)
    {
        $code = $exception->getCode();
        $msg = $exception->getMessage();

        //判断404
        if ($code == 0 && $msg == '页面未找到。') $code = 404;

        if ($exception instanceof CustomException) {

            $res = json_encode(array('code' => $code, 'msg'  => $msg));
            //如果是跨域请求，则执行回调结果
            $callback = Helpers::getRequestParam('callback');
            if (!empty($callback)) $res = $callback . '(' . $res . ')';

            echo $res;

        } elseif ($exception instanceof UnauthorizedHttpException) {

            echo json_encode(array('code' => Lang::ERR_TOKEN_INVALID, 'msg'  => Lang::getMsg(Lang::ERR_TOKEN_INVALID)));

        } else {
            $info = $exception->getTraceAsString();
            \Yii::info("unknown error code: " . $code);
            \Yii::info("unknown error msg: " . $msg);
            \Yii::info('unknown error info: ' . $info);

            echo json_encode(array('code' => 500, 'msg'  => $msg));
        }

        exit;
    }

    /**
     * Handles uncaught PHP exceptions.
     *
     * This method is implemented as a PHP exception handler.
     *
     * @param \Exception $exception the exception that is not caught
     */
    public function handleException($exception)
    {
        if ($exception instanceof ExitException) {
            return;
        }

        $this->exception = $exception;

        // disable error capturing to avoid recursive errors while handling exceptions
        $this->unregister();

        try {
            $this->logException($exception);
            if ($this->discardExistingOutput) {
                $this->clearOutput();
            }
            $this->renderException($exception);
            if (!YII_ENV_TEST) {
                \Yii::getLogger()->flush(true);
                if (defined('HHVM_VERSION')) {
                    flush();
                }
                exit(1);
            }
        } catch (\Exception $e) {
            // an other exception could be thrown while displaying the exception
            $this->handleFallbackExceptionMessage($e, $exception);
        } catch (\Throwable $e) {
            // additional check for \Throwable introduced in PHP 7
            $this->handleFallbackExceptionMessage($e, $exception);
        }

        $this->exception = null;
    }
}