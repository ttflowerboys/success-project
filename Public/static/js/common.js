
/********** 所有ajaxForm提交 ************/
/* 通用表单不带检查操作，失败不跳转 */
$(function () {
    $('.J_ajaxForm').ajaxForm({
        success: complete2, // 这是提交后的方法
        dataType: 'json'
    });
});
/* 通用表单不带检查操作，失败跳转 */
$(function () {
    $('.J_ajaxForm2').ajaxForm({
        success: complete, // 这是提交后的方法
        dataType: 'json'
    });
});
/* 通用含验证码表单不带检查操作，失败不跳转 */
$(function () {
    $('.J_ajaxForm3').ajaxForm({
        success: complete3, // 这是提交后的方法
        dataType: 'json'
    });
});

//失败跳转
function complete(data) {
    if (data.status == 1) {
        layer.alert(data.info, {icon: 6}, function (index) {
            layer.close(index);
            window.location.href = data.url;
        });
    } else {
        layer.alert(data.info, {icon: 5}, function (index) {
            layer.close(index);
            window.location.href = data.url;
        });
        return false;
    }
}
//失败不跳转
function complete2(data) {
    if (data.status == 1) {
        layer.alert(data.info, {icon: 6}, function (index) {
            layer.close(index);
            window.location.href = data.url;
        });
    } else {
        layer.alert(data.info, {icon: 5}, function (index) {
            layer.close(index);
        });
    }
}
//失败不跳转,验证码刷新
function complete3(data) {
    if (data.status == 1) {
        window.location.href = data.url;
    } else {
        $(".J_verifyCode").val('');
        $(".J_verify").click();
        layer.msg(data.info);
    }
}
