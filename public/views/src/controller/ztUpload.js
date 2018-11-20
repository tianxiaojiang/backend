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
        ztUpload = {
            config: {
                uploadUrl: null,
                imgUploadBox: null, //上传节点id
                buttonElem: null,
                TipsP: null,
                showImg: null,
                imgIdInput: null
            },
            mergeConfig: function(){
                if (!ztUpload.config.imgUploadBox) {return false;}
                ztUpload.config.buttonElem = ztUpload.config.imgUploadBox.find('button'),//上传按钮节点
                ztUpload.config.TipsP = ztUpload.config.imgUploadBox.find('p'),//上传提示
                ztUpload.config.showImg = ztUpload.config.imgUploadBox.find('img'),//预览图片节点
                ztUpload.config.imgIdInput = ztUpload.config.imgUploadBox.find('.img_id_value')//上传后的图片id

                ztUpload.render();
            },
            init: function (CustomConfig) {
                if (!CustomConfig.uploadUrl) {console.error('初始化必须有上传地址'); return false;}
                if (!CustomConfig.id) {console.error('初始化必须有上传地址id'); return false;}

                ztUpload.config.uploadUrl = CustomConfig.uploadUrl;
                ztUpload.config.imgUploadBox = CustomConfig.id;

                ztUpload.mergeConfig();
            },
            uploadInst: null,
            render: function(){
                console.log(111);
                ztUpload.uploadInst = upload.render({
                    elem: ztUpload.config.buttonElem
                    ,url: ztUpload.config.uploadUrl
                    ,data: ztutil.createSign({Authorization: ztutil.getToken(), scenario: ztUpload.config.buttonElem.attr("data-scenario")})
                    ,before: function(obj) {
                        //预读本地文件示例，不支持ie8
                        obj.preview(function(index, file, result){
                            ztUpload.config.showImg.attr('src', result); //图片链接（base64）
                        });
                    }
                    ,done: function(res) {
                        //如果上传失败
                        if(res.code !== 0) {
                            //演示失败状态，并实现重传
                            ztUpload.config.TipsP.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                            ztUpload.config.TipsP.find('.demo-reload').on('click', function() {
                                ztUpload.uploadInst.upload();
                            });
                            return layer.msg(res.msg);
                        }
                        //上传成功
                        ztUpload.config.imgIdInput.val(res.data.imgId);
                        ztUpload.config.TipsP.html('<span style="color: #5FB878;">上传成功</span>');
                    }
                    ,error: function(){
                        //演示失败状态，并实现重传
                        ztUpload.config.TipsP.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                        ztUpload.config.TipsP.find('.demo-reload').on('click', function(){
                            ztUpload.uploadInst.upload();
                        });
                    }
                });
            }
        };

    //对外暴露的接口
    exports('ztUpload', ztUpload);
});