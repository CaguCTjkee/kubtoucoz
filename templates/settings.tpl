{{include file="_header.tpl"}}
<!-- Begin page content -->
<div class="container">
    <div class="page-header">
        <div class="settings"><a href="{{$config.home}}" class="btn btn-primary"><i class="glyphicon glyphicon-chevron-left"></i> Назад</a></div>
        <h2>Постинг с videokub.net</h2>
        <p>Настройки</p>
    </div>
    {{if $error}}<div class="alert alert-info">{{$error}}</div>{{/if}}
    <form method="POST" action="{{$config.home}}" id="form">
        <input type="hidden" name="why" value="settings">
        <input type="hidden" name="saw_new" value="{{$config.saw_new}}">
        <input value="1" type="checkbox" {{if $config.add_attach == 1}}checked="checked"{{/if}} name="add_attach" class="hide" id="add_attach">
        <input value="1" type="checkbox" {{if $config.add_tags == 1}}checked="checked"{{/if}} name="add_tags" class="hide" id="add_tags">
        <div class="form-group">
            <label for="site">uCoz сайт</label>
            <input type="text" name="site" class="form-control" id="site" placeholder="http://videokub.at.ua/" value="{{$config.site}}">
        </div>
        <div class="form-group">
            <label for="login">Логин пользователя</label>
            <input type="text" name="login" class="form-control" id="login" placeholder="Логин" value="{{$config.login}}">
        </div>
        <div class="form-group">
            <label for="password">Пароль пользователя</label>
            <input type="text" name="password" class="form-control" id="password" placeholder="Пароль" value="{{$config.password}}">
        </div>
        <div class="alert alert-success">Необходимо создать нового пользователя, который будет входить на сайт по логину\паролю без uID авторизации.</div>
        <div class="form-group">
            <label for="mod">Модуль</label>
            <select name="mod" class="form-control" id="select_cat">
                <option value="news"{{if $config.mod == 'news'}} selected="selected"{{/if}}>Новости</option>
                <option value="blog"{{if $config.mod == 'blog'}} selected="selected"{{/if}}>Блоги</option>
            </select>
            <script type="text/javascript">
                $('[name="mod"]').change(function() {
                    $('.mod-change').removeClass('hidden');
                });
            </script>
        </div>
        <div class="mod-change hidden alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Важно!</strong> После изменения модуля, прежде чем указать категории, нужно сохранить изменения.
        </div>
        {{foreach from=$api.category[0] item=category key=id}}
        <div class="form-group">
            <label for="rubrika[{{$id}}]">Категория {{foreach from=$category[0] item=cat_name}}{{$cat_name}}{{/foreach}}</label>
            <select name="rubrika[{{$id}}]" class="form-control" id="select_cat">
                {{$modcat}}
            </select>
            <script>jQuery('select[name="rubrika[{{$id}}]"]').val('{{$config.rubrika[$id]}}');</script>
        </div>
        {{/foreach}}
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <br>
        <br>
   </form>
</div>
{{include file="_footer.tpl"}}
