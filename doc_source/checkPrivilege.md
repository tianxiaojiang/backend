# 鉴权接口

接口地址： ``` /authentication/system-menu/privileges ```

### 请求参数
| 参数名 | 参数类型 | 是否必须 | 说明 |
| --- | --- | --- | --- |
| sid | int | 是 | 系统id |
| game_id | int | 是 | 游戏id |
| actions | string | 是 | 要检查的操作，多个以英文逗号隔开。例如'/index/news1/index,/index/news1/create' |
| callback | string | 否 | 如果客户端使用jsonp的方式跨域调用，需要传此js会到处理函数名 |
| Authorization | string | 否 | 如果客户端使用jsonp的方式跨域调用，传此参数，就不需要header头里的Authorization |


### 请求header
| 参数名 | 参数类型 | 是否必须 | 说明 |
| --- | --- | --- | --- |
| Authorization | string | 是 | 访问token |

> 如果直接使用html调用此接口，需要把header```Authorization```移到请求参数里去。有跨域限制。


响应结果：

| 字段名 | 字段类型 | 字段说明 |
| --- | --- | --- |
|privileges| object | 权限结果对象 |
|privileges[key]| string | 查询的权限key |
|privileges[].value| bool | 查询的权限val |

结果示例：
```
{
    "code": 0,
    "msg": "",
    "data": {
          'privileges': {
             '/index/news1/index': true,
             '/index/news1/create': false
          }
        }
    }
}
```

