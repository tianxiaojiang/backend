/**

 @Name：layuiAdmin 公共业务
 @Author：贤心
 @Site：http://www.layui.com/admin/
 @License：LPPL

 */

layui.define(function(exports) {
    var $ = layui.$,
        layer = layui.layer,
        laytpl = layui.laytpl,
        setter = layui.setter,
        view = layui.view,
        admin = layui.admin;

    //公共业务的逻辑处理可以写在此处，切换任何页面都会执行

    layui.use(['form', 'element'], function () {
        var form = layui.form,
            element = layui.element;

        var setGameId = function (gameId) {
            layui.data(setter.tableName, {
                key: 'game_id',
                value: gameId
            });
        };
        var getGameId = function (gameId) {
            return layui.data('layuiAdmin').game_id;
        };

        // 首页登录成功，读取并显示用户名
        var getUsername = function () {
            var tplHTML = $('#LAY-temp-username')[0].innerHTML,
                elUsername = $('#LAY-view-username'),
                dataLocal = {
                    username: layui.data('layuiAdmin').username
                };
            laytpl(tplHTML).render(dataLocal, function (html) {
                elUsername.html(html);
            });
        };
        getUsername();

        // 首页获取游戏类型列表
        var getGameList = function (callback) {
               var elGamelist = $('#LAY-game-type-list') ,
                options = {
                    url: '/admin/game/index?r=' + Math.random(),
                    done: function (res) {
                        var storedGameId = getGameId();
                        var HTML  = '';
                        $.each(res.data, function (k, v) {
                            if (!storedGameId) {storedGameId = v.game_id; setGameId(storedGameId)};//没有取第一个存起来
                            HTML += '<option value="' + v.game_id + '"'+ (v.game_id === parseInt(storedGameId) ? ' selected' : '') +'>' + v.name + '</option>';
                        });
                        if (!HTML) HTML = '<option value="0">请联系管理员设置产品</option>';
                        elGamelist.html(HTML);
                        form.render('select', 'layadmin-game-type');

                        if (typeof callback === 'function') callback();
                    }
                } ;
            admin.req(options);

        };

        // 首页监听游戏列表下拉，设置本地游戏id，重新加载并回到首页
        form.on('select(game-type)', function (data) {
            console.log('value: ' + data.value);
            setGameId(data.value);
            window.location.reload();
        });



        // 获取侧边栏菜单
        var getSideMenu = function () {
            var tplHTML = $('#LAY-temp-side-menu')[0].innerHTML,
                elSideMenu = $('#LAY-view-side-menu'),
                options = {
                    url: '/admin/system-menu/show-menus',
                    done: function (res) {
                        laytpl(tplHTML).render(res, function (html) {
                            elSideMenu.html(html);
                        });
                        layui.element.render('nav', 'layadmin-system-side-menu');
                    }
                };
            admin.req(options);
        };

        //先拉取游戏列表，再拉取菜单
        getGameList(getSideMenu);
    });

    // 退出登录
    admin.events.logout = function() {
        // 执行退出接口
        admin.req({
            url: './json/user/logout.js',
            type: 'get',
            data: {},
            done: function(res) { //这里要说明一下：done 是只有 response 的 code 正常才会执行。而 succese 则是只要 http 为 200 就会执行

                // 清空本地记录的 token，并跳转到登入页
                admin.exit();
            }
        });
    };


    //对外暴露的接口
    exports('common', {});
});