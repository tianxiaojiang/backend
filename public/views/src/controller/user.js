/**

 @Name：layuiAdmin 用户登入和注册等
 @Author：贤心
 @Site：http://www.layui.com/admin/
 @License: LPPL

 */

layui.define('form', function(exports) {
    var $ = layui.$,
        layer = layui.layer,
        laytpl = layui.laytpl,
        setter = layui.setter,
        view = layui.view,
        admin = layui.admin,
        form = layui.form;

    var $body = $('body');

    //自定义验证
    form.verify({
        nickname: function(value, item) { //value：表单的值、item：表单的DOM对象
            if (!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)) {
                return '用户名不能有特殊字符';
            }
            if (/(^\_)|(\__)|(\_+$)/.test(value)) {
                return '用户名首尾不能出现下划线\'_\'';
            }
            if (/^\d+\d+\d$/.test(value)) {
                return '用户名不能全为数字';
            }
        },
        //我们既支持上述函数式的方式，也支持下述数组的形式
        //数组的两个值分别代表：[正则匹配、匹配不符时的提示文字]
        pass: [
            /^[\S]{6,12}$/, '密码必须6到12位，且不能出现空格'
        ]
    });


    //发送短信验证码
    $body.on('click', '#LAY-user-getsmscode', function() {
        var $phone = $('#LAY-user-login-cellphone'),
            phone = $phone.val();

        if (!/^1\d{10}$/.test(phone)) {
            $phone.focus();
            return layer.msg('请先输入正确的手机号');
        }

        // 请求发送短信的接口
        admin.req({
            // 实际使用请改成服务端真实接口
            url: './json/user/sms.js',
            data: {
                phone: phone
            },
            done: function(res) {
                layer.msg('验证码已成功发送至您的手机', {
                    offset: '15px',
                    icon: 1,
                    time: 3000
                });
            }
        });
    });


    //更换图形验证码
    $body.on('click', '#LAY-user-get-vercode', function() {
        var othis = $(this);
        this.src = '/admin/token/captcha?t=' + new Date().getTime();
    });

    //对外暴露的接口
    exports('user', {});
});