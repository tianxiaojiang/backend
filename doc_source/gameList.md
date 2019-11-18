# 游戏列表

接口地址： ``` /authentication/game/list ```

### 请求参数
| 参数名 | 参数类型 | 是否必须 | 说明 |
| --- | --- | --- | --- |
| sid | int | 是 | 系统id |
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
| game_id | int | 游戏id |
| name | string | 游戏名 |

结果示例：
```
{
  code: 0,
  msg: "",
  data: {
    games: [
        {game_id: "5012", name: "测试应用"},
        {game_id: "5055", name: "犬夜叉"}
      ]
  }
}
```


