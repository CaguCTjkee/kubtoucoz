<tr{{if $config.saw_new}}{{if $ids[$video.id]}} class="hide"{{/if}}{{/if}}>
    <td class="select-checkbox">
        <label for="cb-select-all-{{$video.id}}"><input id="cb-select-all-{{$video.id}}" type="checkbox" name="post[]" value="{{$video.id}}"></label>
    </td>
    <td><img style="width:50px" src="{{$video.image.150x113[0][1]}}"></td>
    <td>{{$video.title}}</td>
    <td>
        {{foreach from=$video.category[0] item=category name=categorys}}
            {{$category}}{{if !$smarty.foreach.categorys.last}},{{/if}}
        {{/foreach}}
    </td>
    <td>
        {{foreach from=$video.tags[0] item=tag name=tags}}
            {{$tag}}{{if !$smarty.foreach.tags.last}},{{/if}}
        {{/foreach}}
    </td>
    <td>{{$video.description|urldecode|trim|mb_truncate:130:"...":'UTF-8'}}</td>
    <td>{{$video.date}}</td>
</tr>

