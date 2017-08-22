<script id="live_table" type="text/template">
    <div class="user col-lg-24">
        <div class="col-lg-8 user_in">
        <div class="left_image"><img src="{{avatar}}" alt=""></div>
        <div class="user_name"><a href="{{url}}">{{nick}}</a></div>
        <div class="user_info">{{items_count}} skin(s) - <b>{{chance}}%</b> - <b>${{price}}</b></div>
        <div class="user_progress"><div  style="width: {{chance}}%;" class="inside"></div></div>
        </div>
        <div class="col-lg-16 text-left itemsInsideUser ">
            <div class="itemsScroll items_{{steamID}}">

            </div>
        </div>
    </div>
</script>