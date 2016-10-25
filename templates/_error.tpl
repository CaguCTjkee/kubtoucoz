{{include file="_header.tpl"}}

<!-- Begin page content -->
<div class="container">
    <div class="page-header">
        <div class="settings">
            <a href="{{$config.home}}" class="btn btn-primary"><i class="glyphicon glyphicon-chevron-left"></i> Назад</a>
            <a href="{{$config.home}}?mod=settings" class="btn btn-success"><i class="glyphicon glyphicon-cog"></i> Настройки</a>
        </div>
        <h2>Постинг с videokub.net</h2>
        <p>Выбор постинга с сайта videokub.net на uCoz сайт</p>
    </div>
    {{if $error}}<div class="alert alert-info">{{$error}}</div>{{/if}}           
</div>

{{include file="_footer.tpl"}}
