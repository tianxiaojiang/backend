/**

 @Name：上传图片二次封装
 @Author：田卫民

 用法：

 1.引入此模块
 2.传入初始化参数

 示例：
 html:

 <div class="layui-upload" id="systems-update-img-box">
     <input type="hidden" name="img_id" class="img_id_value" value="{{d.params.img_id}}">
     <button type="button" class="layui-btn" id="test1" data-scenario="systems_icon">上传图片</button>
     <div class="layui-upload-list">
         <img class="layui-upload-img" width="100" src="{{d.params.show_url}}">
         <p id="demoText"></p>
     </div>
 </div>

js：
 layui.use(['ztUpload'], function () {

    var ztUpload = layui.ztUpload;

    ztUpload.init({
        uploadUrl: '/admin/file/upload',
        imgUploadBox: $('#systems-update-img-box')
    });

 });

 */

layui.define(['ztutil', 'upload'], function(exports) {
    var upload = layui.upload,
        ztutil = layui.ztutil,
        ztUpload = function() {
            this.config = {
                uploadUrl: null,
                imgUploadBox: null, //上传节点id
                buttonElem: null,
                TipsP: null,
                showImg: null,
                imgIdInput: null
            };
            this.mergeConfig = function(){
                var that = this;
                if (!that.config.imgUploadBox) {return false;}
                that.config.buttonElem = that.config.imgUploadBox.find('button'),//上传按钮节点
                that.config.TipsP = that.config.imgUploadBox.find('p'),//上传提示
                that.config.showImg = that.config.imgUploadBox.find('img'),//预览图片节点
                that.config.imgIdInput = that.config.imgUploadBox.find('.img_id_value')//上传后的图片id

                that.render();
            };
            this.init = function (CustomConfig) {
                var that = this;
                if (!CustomConfig.uploadUrl) {console.error('初始化必须有上传地址'); return false;}
                if (!CustomConfig.id) {console.error('初始化必须有上传地址id'); return false;}

                that.config.uploadUrl = CustomConfig.uploadUrl;
                that.config.imgUploadBox = CustomConfig.id;

                that.mergeConfig();
            };
            this.uploadInst = null;
            this.render = function() {
                var that = this;
                that.uploadInst = upload.render({
                    elem: that.config.buttonElem
                    ,url: that.config.uploadUrl
                    ,data: ztutil.createSign({Authorization: ztutil.getToken(), scenario: that.config.buttonElem.attr("data-scenario")})
                    ,before: function(obj) {
                        //预读本地文件示例，不支持ie8
                        obj.preview(function(index, file, result){
                            that.config.showImg.attr('src', result); //图片链接（base64）
                        });
                    }
                    ,done: function(res) {
                        //如果上传失败
                        if(res.code !== 0) {
                            //演示失败状态，并实现重传
                            that.config.TipsP.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                            that.config.TipsP.find('.demo-reload').on('click', function() {
                                that.uploadInst.upload();
                            });
                            return layer.msg(res.msg);
                        }
                        //上传成功
                        that.config.imgIdInput.val(res.data.imgId);
                        that.config.TipsP.html('<span style="color: #5FB878;">上传成功</span>');
                    }
                    ,error: function(){
                        //演示失败状态，并实现重传
                        that.config.TipsP.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                        that.config.TipsP.find('.demo-reload').on('click', function(){
                            that.uploadInst.upload();
                        });
                    }
                });
            }
        };

        var ztUploadFactory = {
            init: function (config) {
                var uploadInstance = new ztUpload();
                uploadInstance.init(config);
            }
        };

    //对外暴露的接口
    exports('ztUpload', ztUploadFactory);
});