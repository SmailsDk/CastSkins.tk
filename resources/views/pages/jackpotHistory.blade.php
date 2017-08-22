@extends('welcome')
@section('page_title') Jackpot History @stop

@section('content')
    <div class="container">
        <table class="table user_history_table">
            <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Jackpot</th>
                <th>Chance</th>
                <th>Rate</th>
                <th>Items</th>
            </tr>
            </thead>
            <tbody>
            @foreach($getJackpotHistory as $history)

                <tr>
                    <td>{{$history['data']->id}}</td>
                    <td><img class="img-circle" style="margin-right: 5px;" src="{{$history['user']->avatar}}" width="20" alt="">{{$history['user']->nick}}</td>
                    <td>${{$history['data']->potPrice / 100}}</td>
                    <td>{{round(($history['data']->userPutInPrice / $history['data']->potPrice)*100)}}%</td>
                    <td>${{$history['data']->userPutInPrice / 100}}</td>
                    <td>
                        <button class="btn-danger btn-sm"
                                onclick="$('.item_id_{{$history['data']->id}}').toggle('slow');">Show/Hide items
                        </button>
                    </td>
                </tr>
                <tr class="item_id_{{$history['data']->id}}" style="display: none;">
                    <td style="border-top: 0;width: 100%;" colspan="6">
                        @foreach(json_decode($history['data']->allItemsJson) as $item)
                            <div class="pull-left itemHist">
                                <img class="tooltip"
                                     title="&lt;div style='text-align:center;'&gt;{{$item->itemName}} &lt;br&gt; &lt;strong&gt; Item Price : ${{$item->itemPrice/100}} &lt;/strong&gt;&lt;/div&gt;"
                                     src="http://steamcommunity-a.akamaihd.net/economy/image/{{$item->itemIcon}}/90fx90f"
                                     alt="">
                            </div>
                        @endforeach
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

    </div>
@stop

@section('scripts_dif')
    <script>
        $(window).load(function() {
            $('.tooltip').each(function() // Select all elements with the "tooltip" attribute
            {
                $(this).tooltipster({
                    animation: 'swing',
                    content: this['title'],
                    multiple: true,
                    position: 'top',
                    contentAsHTML: true
                });

            });
        });
    </script>
@stop