
    <form class="form-inline" enctype="multipart/form-data" action="{{ route('admin.config.update') }}" method="post">
        {{ csrf_field() }}
        <table class="table table-striped table-bordered table-hover">
            <tr>
                <th  class="col-lg-1">首页网站标题：</th>
                <th class="col-lg-10" >
                    <textarea class="form-control modal-sm"  name="index_site_name" rows="2" style="width: 80%">{{  config('index_site_name') }}</textarea>
                </th>
            </tr>
            <tr>
                <th>首页网站关键字：</th>
                <td>
                    <textarea class="form-control modal-sm" name="index_seo_keyword" rows="2" style="width: 80%">{{  config('index_seo_keyword') }}</textarea>
                </td>
            </tr>
            <tr>
                <th>首页网站描述：</th>
                <td>
                    <textarea class="form-control modal-sm" name="index_seo_description" rows="2" style="width: 80%">{{  config('index_seo_description') }}</textarea>
                </td>
            </tr>

            <tr>
                <th>联系邮箱：</th>
                <td>
                    <textarea class="form-control modal-sm" name="contact_email" rows="2" style="width: 80%">{{  config('contact_email') }}</textarea>
                </td>
            </tr>

            <tr>
                <th></th>
                <td>
                    <input class="btn btn-success" type="submit" value="提交">
                </td>
            </tr>
        </table>
    </form>
