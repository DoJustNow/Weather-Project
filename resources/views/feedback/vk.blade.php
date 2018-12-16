<script type="text/javascript">
    window.vkAsyncInit = function() {
        VK.init({
        apiId: 6743977,
        onlyWidgets: true
        });
        VK.Widgets.Comments('vk_comments', {
            limit: 7,
            autoPublish: 1,
            pageUrl: 'weather-service.mcdir.ru/feedback/vk'
        });
    };
    setTimeout(function() {
        var el = document.createElement("script");
        el.type = "text/javascript";
        el.src = "https://vk.com/js/api/openapi.js?160";
        el.async = true;
        document.getElementById("vk_comments").appendChild(el);
    }, 0);
</script>
<div class="container">
    <div class="justify-content-center">
        <div id="vk_comments"></div>
    </div>
</div>
