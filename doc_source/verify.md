# 鉴权接口

接口地址： ``` /open/privilege/check ```

### 请求参数
| 参数名 | 参数类型 | 是否必须 | 说明 |
| --- | --- | --- | --- |
| sid | int | 是 | 系统id |
| m | string | 是 | module |
| c | string | 是 | controller |
| a | string | 是 | action |
| game_id | string | 是 | 游戏id |


### 请求header
| 参数名 | 参数类型 | 是否必须 | 说明 |
| --- | --- | --- | --- |
| Authorization | string | 是 | 访问token |


响应结果：

| 字段名 | 字段类型 | 字段说明 |
| --- | --- | --- |

结果示例：
```
{
    "code": 0,
    "msg": "",
    "data": {}
    }
}
```

