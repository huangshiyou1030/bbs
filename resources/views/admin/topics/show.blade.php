<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">游戏详情</h3>
        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 10px">
                <a href="{{ route('admin.topics.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i> 列表</a>
            </div>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <tbody>
            <tr>
                <td>标题：</td>
                <td>{{$topics->title}}</td>

            </tr>
            <tr>
                <td>作者：</td>
                <td>{{$topics->user->name }}</td>

            </tr>
            <tr>
                <td>分类：</td>
                <td>{{$topics->category->name }}</td>

            </tr>

                <tr>
                    <td>封面图片：</td>
                    <td>

                    <img src="{{ $topics->indeximage}}"/>

                    </td>

                </tr>

                <tr>
                    <td>详情：</td>


                    <td>{!! $topics->body !!}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {

        // 『不同意』按钮的点击事件
        $('#btn-refund-disagree').click(function() {
            swal({
                title: '输入拒绝理由',
                input: 'text',
                showCancelButton: true,
                confirmButtonText: "确认",
                cancelButtonText: "取消",
                showLoaderOnConfirm: true,
                preConfirm: function(inputValue) {
                    if (!inputValue) {
                        swal('理由不能为空', '', 'error')
                        return false;
                    }
                    // Laravel-Admin 没有 axios，使用 jQuery 的 ajax 方法来请求
                    return $.ajax({
                        url: '{{ route('admin.topics.handleState', [$topics->id]) }}',
                        type: 'POST',
                        data: JSON.stringify({   // 将请求变成 JSON 字符串
                            agree: false,  // 拒绝
                            reason: inputValue,
                            // 带上 CSRF Token
                            // Laravel-Admin 页面里可以通过 LA.token 获得 CSRF Token
                            _token: LA.token,
                        }),
                        contentType: 'application/json',  // 请求的数据格式为 JSON
                    });
                },
                allowOutsideClick: () => !swal.isLoading()
            }).then(function (ret) {
                // 如果用户点击了『取消』按钮，则不做任何操作
                if (ret.dismiss === 'cancel') {
                    return;
                }
                swal({
                    title: '操作成功',
                    type: 'success'
                }).then(function() {
                    // 用户点击 swal 上的按钮时刷新页面
                    location.reload();
                });
            });
        });

        // 『同意』按钮的点击事件
        $('#btn-refund-agree').click(function() {
            swal({
                title: '确定通过吗？',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: "确认",
                cancelButtonText: "取消",
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return $.ajax({
                        url: '{{ route('admin.topics.handleState', [$topics->id]) }}',
                        type: 'POST',
                        data: JSON.stringify({
                            agree: true, // 代表同意
                            _token: LA.token,
                        }),
                        contentType: 'application/json',
                    });
                }
            }).then(function (ret) {
                // 如果用户点击了『取消』按钮，则不做任何操作
                if (ret.dismiss === 'cancel') {
                    return;
                }
                swal({
                    title: '操作成功',
                    type: 'success'
                }).then(function() {
                    // 用户点击 swal 上的按钮时刷新页面
                    location.reload();
                });
            });
        });
    });
</script>