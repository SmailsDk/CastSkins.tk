<script id="history_round_users" type="text/template">
    <tr class="{{class}}">
        <td style="width: 350px;">
            <div id="user_image" style="float: left;width: 30px;height: 30px;margin: 0;" class="img-circle">
                <img class="img-circle" style="width: 90%;" src="{{avatar}}" >
            </div>
            <a style="color:white;float: left;margin-left: 5px;padding-top: 4px;text-decoration: none;" href="{{url}}" target="_blank">{{nick}}</a>
        </td>
        <td>{{pot_price}}</td>
        <td>{{chance}}</td>
        <td>{{items}} ITEMS</td>
        <td>{{rate}}</td>
    </tr>
</script>

<script id="history_round" type="text/template">
    <div class="table_head">History round : <b class="liveround_text">#{{id}}</b> &nbsp; &nbsp; Hash : <b class="liveroundhash">{{hash}}</b></div>
    <table class="table table_user" style="color: #FFF;">
    <thead>
    <tr>
        <th>Player</th>
        <th>Jackpot</th>
        <th>Chance</th>
        <th>Items</th>
        <th>Rate</th>
    </tr>
    </thead>
        <tbody id="history_round_{{id}}">

        </tbody>
    </table>
</script>