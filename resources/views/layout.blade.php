<!DOCTYPE html>
<html lang="zh-TW">
  <head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
    <meta property="og:site_name" content="{{ $config['site_name'] }}" />
    <meta property="og:title" content="{{ $config['site_title'] }}" />
    <meta property="og:description" content="{{ $config['site_description'] }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url() }}" />
    <meta property="og:image" content="{{ url('/images/fb.png') }}" />
    <title>{{ $config['site_title'] }}</title>
    <link rel="stylesheet" href="/vendor/theme/{{ $config['bootstrap_theme'] }}/bootstrap.min.css">
    <link rel="stylesheet" href="/css/all.css">
    <!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
    <![endif]-->
    <script src="/vendor/jquery-1.11.3.min.js"></script>
    <script src="/vendor/bootstrap.min.js"></script>
    <!--
    　　　　　　　　　　　　　　　　　　ㄚ心代淰頑榨踣榨紳家畀近牛人
    　　　　　　　　　　　　　　　汁硫陡草艾上彳ㄚ　　　　卜ㄚ小尺泛哽爬合了
    　　　　　　　　　　　　人官胞自二　　　　　　　ㄚㄚ　　　　　　　ㄚ三竟富日
    　　　　　　　　　　彳咖娛寸　　　　卜ㄚ二人ㄚㄚ十二ㄚㄚ卜彳彳ㄚ　　　　卜乞偏山
    　　　　　　　　卜抔飛上　　　卜人彳卜　　　　　　　　　　　　卜二人ㄚ　　　ㄚ丹嗀下
    　　　　　　　干撚次　　　卜人卜　　　　　　　　　　　　　　　　　　卜了ㄚ　　　二濟泛
    　　　　　　三撚了　　　人卜　　　　　　　　　　ㄚ十　　　　　　　　　　卜人　　　　刓提卜
    　　　　　尤嫩ㄚ　　　彳　　　　　　　　　　上凡陷敨爪上　　　　　　　　　　人ㄚ　　　力縣ㄚ
    　　　　才端ㄚ　　　彳　　　　　　　　　亍迎誤炮寒康濾競崎正ㄚ　　　　　　　　ㄚ卜　　　以貨
    　　　十畫卜　　　二　　　　　　　　尸范盲書課窮鐐鱸聰盪教發怒下　　　　　　　　了ㄚ　　　灰官
    　　　鮮入　　　彳　　　　　　　人昨遶籬飆钁麝鬣麝钁礱鑿鬣礱籌靈皈人　　　　　　　二　　　　賞九
    　　爪妨　　　ㄚ卜　　　　　斤買躩飆鑼礱鬣鑿麝礱鬣麝钁鬣鑿鑼礱钁麝戇蒔仃卜　　　　　人　　　二綠
    　　聯卜　　　人　　　　卜狡麝飆钁钁鑼钁飆钁飆鬣鑿钁钁礱鑿鑿飆鬣鑿钁钁鑼擁丁　　　　十　　　　聆爪
    　斗揪　　　卜ㄚ　　　　下礱麝麝飆鬣钁飆竇糟檸使攻註近跤擎攀飆鑼鬣鑿礱钁钁麝止　　　　了　　　干縣
    　從凡　　　二　　　　　　亞鑿鑼鑼鑿貓卡ㄚ　　　　　　　　　人年攬鑿麝鬣钁噦卜　　　　了　　　　趜卜
    　檯ㄚ　　　人　　　　　　　亍墨騙演　　　　　　　　　　　　　　卜設鏨蠢泥　　　　　　十　　　　話六
    ㄚ歷　　　　人　　　　　　　　卜ㄚ　　　　　　　　　　　　　　　　　十卜　　　　　　　二　　　　世印
    十滷　　　　了　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　十　　　　沾呢
    了喝　　　　彳　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　二　　　　古串
    卜奮　　　　ㄚ　　　　People should not be afraid of their governments　　　十　　　　以刺
    　動卜　　　　ㄚ　　　　Governments should be afraid of their people 　　　卜ㄚ　　　　泥父
    　弛尸　　　　彳　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　二　　　　　銇了
    　兄吮　　　　ㄚㄚ　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　ㄚ二　　　　十奮
    　ㄚ糧卜　　　　人卜　　　　　　　了生　　　　　　　　　　　　　畀止　　　　　　　十　　　　　赤步
    　　狂勿　　　　　二　　　　　　允鬱飆立　　　　　　　　　　　令麝钁臥　　　　　二　　　　　ㄚ曉ㄚ
    　　卜遞二　　　　　人　　　　冇籬麝飆光　　　　　　　　　　　虫礱鑿鬣臭口人　十卜　　　　　拜沉
    　　　仁絲　　　　　　工川洋毋糶飆礱麝坐　　　　　　　　　　　楓麝鑼鑿飄縛籌罐斂加戶二　　才圃
    　　　　死浡　ㄚ仃姑鏡驢鏤酇栗醫麝鑿飆飆比　　　　　　　　ㄚ搓鬣礱麝鬣鷹對葛豔醫鑼麝鬣礫誌蝠卜
    　　　　　湖灌醹囓囊糶鹽轟各攙翻麝飆鬣鑿钁蟻岔下卜卜ㄚ入門稽飆麝礱鬣钁蠢鑊洽籌盦礱钁麝鑿鹽了
    　　　　　　豆躩繼翻翻蠢麵步豔齧盦鬣礱钁麝鑿麝鑿礱籌鑢鬣麝鑿飆鑿麝鑿壅鹹鷹幅囊殭鑿钁礱繷十
    　　　　　　　炒囊囓糶釀霋攔騙騙盦騙礱鑿钁麝礱鑿钁钁钁鬣鬣礱鑿钁鑿戇纂軍纂驢櫃蟠飆钁縮卜
    　　　　　　　　小鋼鏤籬痠繼翻鏤籬蠶籌鑿礱鑼鑿飆钁鑼鬣鬣鑿鑿麝钁麵邋醫羚豔蠢籠鹹邋台
    　　　　　　　　　　正獵撫籬靄翻鏤醒重躩籌蠶麝礱鑼飄鑼鑿礱麝殭轟騙重撬幸蟠囓攬氧人
    　　　　　　　　　　　ㄚ行窯齧醹鏤麵纂鹽繼鹽醒鹹壅鴉翻靄蠢繼糶蠶蟠囊癰蹶鏤荷人
    　　　　　　　　　　　　　　土怨達鹹蟠鹽麵重鱒豔罷拜囓翻糶翻盦靄钁囊銜山卜
    　　　　　　　　　　　　　　　　　二平訌挷霸遽翻煽梳騙釀融禍消句下卜
    　　　　　　　　　　　　　　　　　　　　　　　　ㄚ卜卜
    -->
  </head>
  <body>
    @yield('content')
  </body>
</html>
