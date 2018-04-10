<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace Backend\components;

use Backend\helpers\Helpers;
use Backend\helpers\Lang;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\ResponseFormatterInterface;

/**
 * 重新格式化json输出格式
 */
class JsonOutput extends Component implements ResponseFormatterInterface
{
    /**
     * JSON Content Type
     * @since 2.0.14
     */
    const CONTENT_TYPE_JSONP = 'application/javascript; charset=UTF-8';
    /**
     * JSONP Content Type
     * @since 2.0.14
     */
    const CONTENT_TYPE_JSON = 'application/json; charset=UTF-8';
    /**
     * HAL JSON Content Type
     * @since 2.0.14
     */
    const CONTENT_TYPE_HAL_JSON = 'application/hal+json; charset=UTF-8';

    /**
     * @var string|null custom value of the `Content-Type` header of the response.
     * When equals `null` default content type will be used based on the `useJsonp` property.
     * @since 2.0.14
     */
    public $contentType;
    /**
     * @var bool whether to use JSONP response format. When this is true, the [[Response::data|response data]]
     * must be an array consisting of `data` and `callback` members. The latter should be a JavaScript
     * function name while the former will be passed to this function as a parameter.
     */
    public $useJsonp = false;
    /**
     * @var int the encoding options passed to [[Json::encode()]]. For more details please refer to
     * <http://www.php.net/manual/en/function.json-encode.php>.
     * Default is `JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE`.
     * This property has no effect, when [[useJsonp]] is `true`.
     * @since 2.0.7
     */
    public $encodeOptions = 320;
    /**
     * @var bool whether to format the output in a readable "pretty" format. This can be useful for debugging purpose.
     * If this is true, `JSON_PRETTY_PRINT` will be added to [[encodeOptions]].
     * Defaults to `false`.
     * This property has no effect, when [[useJsonp]] is `true`.
     * @since 2.0.7
     */
    public $prettyPrint = false;


    /**
     * Formats the specified response.
     * @param Response $response the response to be formatted.
     */
    public function format($response)
    {
        if ($this->contentType === null) {
            $this->contentType = $this->useJsonp
                ? self::CONTENT_TYPE_JSONP
                : self::CONTENT_TYPE_JSON;
        } elseif (strpos($this->contentType, 'charset') === false) {
            $this->contentType .= '; charset=UTF-8';
        }
        $response->getHeaders()->set('Content-Type', $this->contentType);

        if ($this->useJsonp) {
            $this->formatJsonp($response);
        } else {
            $this->formatJson($response);
        }
    }

    /**
     * Formats response data in JSON format.
     * @param Response $response
     */
    protected function formatJson($response)
    {
        if ($response->data !== null) {
            $options = $this->encodeOptions;
            if ($this->prettyPrint) {
                $options |= JSON_PRETTY_PRINT;
            }

            //自定义输出格式
            $res = [
                'code'      => 0,
                'msg'   => '成功',
                'data' => []
            ];

            if (isset($response->data['code'])) {
                $res['code']    = empty($response->data['code']) ? $response->data['status'] : $response->data['code'];
                if ($res['code'] == 401) {
                    //将系统的登录错误码，统一为自定义错误码
                    $res['code'] = Lang::ERR_TOKEN_INVALID;
                }
                $res['msg'] = $response->data['message'];
                //var_dump($response->data);exit;
                //Helpers::info('== error trace: ' . var_export($response->data['stack-trace'], true));
            } else {
                $res['data'] = $response->data;
            }

            //有分页的将分页和数据拆分出来，适应客户端格式
            if (isset($res['data']['count']) && isset($res['data']['lists'])) {
                $res['count'] = $res['data']['count'];
                $res['data'] = $res['data']['lists'];
            }

            $response->content = Json::encode($res, $options);
        }
    }

    /**
     * Formats response data in JSONP format.
     * @param Response $response
     */
    protected function formatJsonp($response)
    {
        if (is_array($response->data)
            && isset($response->data['data'], $response->data['callback'])
        ) {
            $response->content = sprintf(
                '%s(%s);',
                $response->data['callback'],
                Json::htmlEncode($response->data['data'])
            );
        } elseif ($response->data !== null) {
            $response->content = '';
            Yii::warning(
                "The 'jsonp' response requires that the data be an array consisting of both 'data' and 'callback' elements.",
                __METHOD__
            );
        }
    }
}
