{{include file="_header.tpl"}}
<!-- Begin page content -->
<div class="container">
    <div class="page-header">
        <div class="settings"><a href="{{$config.home}}?mod=settings" class="btn btn-success"><i class="glyphicon glyphicon-cog"></i> Настройки</a></div>
        <h2>Постинг с videokub.net</h2>
        <p>Выбор постинга с сайта videokub.net на uCoz сайт</p>
    </div>
    {{if $error}}<div class="alert alert-info">{{$error}}</div>{{/if}}
    <p class="lead">Добавлять видеозаписи можно как последние, так и по <a href="#category" onclick="return false;">категориям</a>. Так-же можно указать <a href="#items_per_page" onclick="return false;">количество</a> и выбрать <a href="#" onclick="$('.template').toggle('slow');
            return false;">шаблон добавления</a>.</p>
    <form method="POST" action="{{$config.home}}" id="form">

        <input type="hidden" name="why" value="enter">
        <input type="hidden" name="url" value="{{$api_url}}{{$api_param}}">
        <div class="template">
            <p>В шаблоне используются теги: <b>[video=680x450]</b>, <b>[description]</b>, <b>[title]</b>, <b>[image]</b></p>
            <textarea name="fullstory" style="width:100%; height:200px;">{{$config.fullstory}}</textarea>
            <div class="form-inline">
                <div class="form-group">
                    <input value="1" type="checkbox" {{if $config.add_attach == 1}}checked="checked"{{/if}} name="add_attach" id="add_attach">
                    <label for="add_attach">Грузить картинку на сайт</label>
                </div>
                <div class="form-group">
                    <input value="1" type="checkbox" {{if $config.add_tags == 1}}checked="checked"{{/if}} name="add_tags" id="add_tags">
                    <label for="add_tags">Добавлять теги</label>
                </div>
                <div class="form-group">
                    <label for="mod">Модуль</label>
                    <select name="mod" class="form-control" id="select_cat">
                        <option value="news"{{if $config.mod == 'news'}} selected="selected"{{/if}}>Новости</option>
                        <option value="blog"{{if $config.mod == 'blog'}} selected="selected"{{/if}}>Блоги</option>
                    </select>
                </div>
                <div class="form-group">
                    <input value="1" type="checkbox" {{if $config.saw_new == 1}}checked="checked"{{/if}} name="saw_new" id="saw_new">
                    <label for="saw_new">Показывать только новые</label>
                </div>
                <div class="form-group">
                    <input type="submit" class="form-control btn btn-primary" value="Сохранить" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <div class="input-group">
                    <select name="post_mod" class="form-control">
                        <option value="publish" selected="selected">Опубликовать</option>
                        <option value="draft">В черновик</option>
                    </select>
                    <a href="#" id="doaction" class="btn input-group-addon">Применить</a>
                </div>
            </div>
            <div class="col-xs-5 form-inline">
                <div class="form-group">
                    <select name="count" class="form-control">
                        <option value="10"{{if $smarty.get.count == 10}} selected="selected"{{/if}}>10</option>
                        <option value="20"{{if $smarty.get.count == 20}} selected="selected"{{/if}}>20</option>
                        <option value="50"{{if $smarty.get.count == 50}} selected="selected"{{/if}}>50</option>
                        <option value="100"{{if $smarty.get.count == 100}} selected="selected"{{/if}}>100</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="category" class="form-control">
                        <option value="" {{if !$smarty.get.category}}selected="selected"{{/if}}>Все категории</option>
                        {{foreach from=$api.category[0] key=id item=cats}}
                        {{foreach from=$cats[0] key=key item=cat}}
                        <option value="{{$key}}"{{if $smarty.get.category == $key}} selected="selected"{{/if}}>{{$cat}}</option>
                        {{/foreach}}
                        {{/foreach}}
                    </select>
                </div>
                <button type="submit" class="btn btn-default">Фильтр</button>
            </div>
            <div class="col-xs-4">
                <nav>
                    <ul class="pagination row">
                        <li class="col-xs-2">
                            <a href="{{$pagination.firstpage}}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="col-xs-2">
                            <a href="{{$pagination.prevpage}}" aria-label="Previous">
                                <span aria-hidden="true">&#8249;</span>
                            </a>
                        </li>
                        <li class="col-xs-3"><input type="text" name="page" id="page" class="form-control" value="{{$smarty.get.page|default:"1"}}" /></li>
                        <li class="col-xs-2">
                            <a href="{{$pagination.nextpage}}" aria-label="Next">
                                <span aria-hidden="true">&#8250;</span>
                            </a>
                        </li>
                        <li class="col-xs-2">
                            <a href="{{$pagination.lastpage}}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <table class="table table-bordered">
                    <tr class="active">
                        <td class="select-checkbox">
                            <label for="cb-select-all-1">
                                <input id="cb-select-all-1" type="checkbox">
                            </label>
                        </td>
                        <td></td>
                        <td>Заголовок</td>
                        <td>Рубрики</td>
                        <td>Теги</td>
                        <td>Описание</td>
                        <td>Дата</td>
                    </tr>
                    {{foreach from=$api.videos item=video}}
                    {{include file='table.tpl'}}
                    {{/foreach}}
                    <tr class="active">
                        <td class="select-checkbox">
                            <label for="cb-select-all-2">
                                <input id="cb-select-all-2" type="checkbox">
                            </label>
                        </td>
                        <td></td>
                        <td>Заголовок</td>
                        <td>Рубрики</td>
                        <td>Теги</td>
                        <td>Описание</td>
                        <td>Дата</td>
                    </tr>
                </table>
            </div>
        </div>
    </form>

</div>
{{include file="_footer.tpl"}}
