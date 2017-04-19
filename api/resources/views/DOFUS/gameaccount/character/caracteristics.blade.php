@extends('layouts.contents.default')
@include('layouts.menus.base')
@section('header')
    {!! Html::style('css/directories.css') !!}
    {!! Html::style('css/encyclopedia.css') !!}
@stop
@section('breadcrumbs')
{? $page_name = config('dofus.subtitle') ?}
{!! Breadcrumbs::render('home') !!}
@stop
@section('beta')
    <div class="ak-beta"></div>
@endsection
@section('content')
<div class="ak-container ak-main-center">
   <div class="ak-title-container ak-backlink">
      <h1><span class="ak-icon-big ak-character"></span></a>{{$character->Name}}</h1>
   </div>
   <div class="ak-page-menu">
      <nav class="ak-nav-expand ">
         <div class="ak-nav-expand-container">
            <ul class="ak-nav-links ak-nav-expand-links">
               <li><a href="{{route('characters.view', [$server, $character->Id, $character->Name])}}">Profil</a></li>
               <li class="on"><a href="{{route('characters.caracteristics', [$server, $character->Id, $character->Name])}}">Caractéristiques</a></li>
            </ul>
         </div>
      </nav>
   </div>
   <div class="ak-container ak-panel ak-nocontentpadding">
      <div class="ak-panel-content">
         <div class="ak-directories-header ">
            <div class="ak-directories-icon">
               <div class="ak-entitylook" alt="" style="background:url({{ DofusForge::player($character, $server, 'face', 2, 35, 35) }}) top left;width:35px;height:35px">
               </div>
            </div>
            <div class="ak-directories-main-infos">
               <div class="ak-directories-property">
                  <span class="ak-directories-level">Niveau {{$character->level()}}</span>@if($character->PrestigeRank > 0)<span class="ak-directories-prestige"> - Prestige {{$character->PrestigeRank}}</span>@endif<br>
                  <span class="ak-directories-breed">{{$character->classe()}}</span>
               </div>
               @if($character->titleActive($server))
                <div class="ak-directories-property ak-directories-property-grade">
                    <span class="ak-directories-grade">{!! $character->titleActive($server)->name() !!}</span>
                </div>
               @endif
               <div class="ak-directories-property ak-directories-property-server">
                  <span class="server">
                  <span class="server_4001"></span>
                  <span class="ak-directories-server-name">{{ucfirst($server)}}</span>
                  </span>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="ak-container ak-panel-stack ak-directories ak-glue">
       <div class="ak-container ak-panel ak-caracteristics-content">
            <div class="ak-panel-title">
                <span class="ak-panel-title-icon"></span>
                Équipement            
            </div>
            <div class="ak-panel-content">
                <div class="hidden-xs">
                    <div class="ak-equipment-columns">
                    <div class="ak-equipment-left">
                        <div class="ak-equipment-item ak-equipment-shield" id="ak-dofus-character-equipment-item-shield">
                            @if($itemsleft->containsStrict('Position', \App\ItemPosition::ACCESSORY_POSITION_SHIELD))
                            @php $item = $itemsleft->where('Position', \App\ItemPosition::ACCESSORY_POSITION_SHIELD)->first() @endphp
                            <span class="ak-linker" data-hasqtip="linker_item_{{$item->ItemId}}"><img src="{{DofusForge::item($item->Template->IconId, 52)}}"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_{{$item->ItemId}}","linker-path":"{{URL::route('linker.get', 'item')}}","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"{{$item->ItemId}}","character":"{{$character->Id}}","server":"{{$server}}","position":"left","builder":""}}</script>                        
                            @endif
                        </div>
                        <div class="ak-equipment-item ak-equipment-amulet" id="ak-dofus-character-equipment-item-amulet">
                            @if($itemsleft->containsStrict('Position', \App\ItemPosition::ACCESSORY_POSITION_AMULET))
                            @php $item = $itemsleft->where('Position', \App\ItemPosition::ACCESSORY_POSITION_AMULET)->first() @endphp
                            <span class="ak-linker" data-hasqtip="linker_item_{{$item->ItemId}}"><img src="{{DofusForge::item($item->Template->IconId, 52)}}"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_{{$item->ItemId}}","linker-path":"{{URL::route('linker.get', 'item')}}","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"{{$item->ItemId}}","character":"{{$character->Id}}","server":"{{$server}}","position":"left","builder":""}}</script>                      
                            @endif                    
                        </div>
                        <div class="ak-equipment-item ak-equipment-ring1" id="ak-dofus-character-equipment-item-ring1">
                            @if($itemsleft->containsStrict('Position', \App\ItemPosition::INVENTORY_POSITION_RING_LEFT))
                            @php $item = $itemsleft->where('Position', \App\ItemPosition::INVENTORY_POSITION_RING_LEFT)->first() @endphp
                            <span class="ak-linker" data-hasqtip="linker_item_{{$item->ItemId}}"><img src="{{DofusForge::item($item->Template->IconId, 52)}}"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_{{$item->ItemId}}","linker-path":"{{URL::route('linker.get', 'item')}}","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"{{$item->ItemId}}","character":"{{$character->Id}}","server":"{{$server}}","position":"left","builder":""}}</script>                        
                            @endif 
                        </div>
                        <div class="ak-equipment-item ak-equipment-cap" id="ak-dofus-character-equipment-item-cap">
                            @if($itemsleft->containsStrict('Position', \App\ItemPosition::ACCESSORY_POSITION_CAPE))
                            @php $item = $itemsleft->where('Position', \App\ItemPosition::ACCESSORY_POSITION_CAPE)->first() @endphp
                            <span class="ak-linker" data-hasqtip="linker_item_{{$item->ItemId}}"><img src="{{DofusForge::item($item->Template->IconId, 52)}}"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_{{$item->ItemId}}","linker-path":"{{URL::route('linker.get', 'item')}}","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"{{$item->ItemId}}","character":"{{$character->Id}}","server":"{{$server}}","position":"left","builder":""}}</script>                      
                            @endif 
                        </div>
                        <div class="ak-equipment-item ak-equipment-boots" id="ak-dofus-character-equipment-item-boots">
                            @if($itemsleft->containsStrict('Position', \App\ItemPosition::ACCESSORY_POSITION_BOOTS))
                            @php $item = $itemsleft->where('Position', \App\ItemPosition::ACCESSORY_POSITION_BOOTS)->first() @endphp
                            <span class="ak-linker" data-hasqtip="linker_item_{{$item->ItemId}}"><img src="{{DofusForge::item($item->Template->IconId, 52)}}"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_{{$item->ItemId}}","linker-path":"{{URL::route('linker.get', 'item')}}","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"{{$item->ItemId}}","character":"{{$character->Id}}","server":"{{$server}}","position":"left","builder":""}}</script>                        
                            @endif 
                        </div>
                    </div>
                    <div class="ak-equipment-middle">
                        <div class="ak-character-equipments">
                            <div class="ak-chracter-illustration">
                                <div class="ak-set-illu">
                                <div class="ak-entitylook" alt="" style="background:url({{ DofusForge::player($character, $server, 'full', 1, 270, 361, 10) }}) top left;width:270px;height:361px"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ak-equipment-right">
                        <div class="ak-equipment-item ak-equipment-weapon" id="ak-dofus-character-equipment-item-weapon">
                            @if($itemsright->containsStrict('Position', \App\ItemPosition::ACCESSORY_POSITION_WEAPON))
                            @php $item = $itemsright->where('Position', \App\ItemPosition::ACCESSORY_POSITION_WEAPON)->first() @endphp
                            <span class="ak-linker" data-hasqtip="linker_item_{{$item->ItemId}}"><img src="{{DofusForge::item($item->Template->IconId, 52)}}"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_{{$item->ItemId}}","linker-path":"{{URL::route('linker.get', 'item')}}","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"{{$item->ItemId}}","character":"{{$character->Id}}","server":"{{$server}}","position":"right","builder":""}}</script>                          
                            @endif
                        </div>
                        <div class="ak-equipment-item ak-equipment-hat" id="ak-dofus-character-equipment-item-hat">
                            @if($itemsright->containsStrict('Position', \App\ItemPosition::ACCESSORY_POSITION_HAT))
                            @php $item = $itemsright->where('Position', \App\ItemPosition::ACCESSORY_POSITION_HAT)->first() @endphp
                            <span class="ak-linker" data-hasqtip="linker_item_{{$item->ItemId}}"><img src="{{DofusForge::item($item->Template->IconId, 52)}}"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_{{$item->ItemId}}","linker-path":"{{URL::route('linker.get', 'item')}}","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"{{$item->ItemId}}","character":"{{$character->Id}}","server":"{{$server}}","position":"right","builder":""}}</script>                      
                            @endif
                        </div>
                        <div class="ak-equipment-item ak-equipment-ring2" id="ak-dofus-character-equipment-item-ring2">
                            @if($itemsright->containsStrict('Position', \App\ItemPosition::INVENTORY_POSITION_RING_RIGHT))
                            @php $item = $itemsright->where('Position', \App\ItemPosition::INVENTORY_POSITION_RING_RIGHT)->first() @endphp
                            <span class="ak-linker" data-hasqtip="linker_item_{{$item->ItemId}}"><img src="{{DofusForge::item($item->Template->IconId, 52)}}"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_{{$item->ItemId}}","linker-path":"{{URL::route('linker.get', 'item')}}","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"{{$item->ItemId}}","character":"{{$character->Id}}","server":"{{$server}}","position":"right","builder":""}}</script>                       
                            @endif                   
                        </div>
                        <div class="ak-equipment-item ak-equipment-belt" id="ak-dofus-character-equipment-item-belt">
                            @if($itemsright->containsStrict('Position', \App\ItemPosition::ACCESSORY_POSITION_BELT))
                            @php $item = $itemsright->where('Position', \App\ItemPosition::ACCESSORY_POSITION_BELT)->first() @endphp
                            <span class="ak-linker" data-hasqtip="linker_item_{{$item->ItemId}}"><img src="{{DofusForge::item($item->Template->IconId, 52)}}"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_{{$item->ItemId}}","linker-path":"{{URL::route('linker.get', 'item')}}","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"{{$item->ItemId}}","character":"{{$character->Id}}","server":"{{$server}}","position":"right","builder":""}}</script>                     
                            @endif                  
                        </div>
                        <div class="ak-equipment-item ak-equipment-pet" id="ak-dofus-character-equipment-item-pet">
                            @if($itemsright->containsStrict('Position', \App\ItemPosition::ACCESSORY_POSITION_PETS))
                            @php $item = $itemsright->where('Position', \App\ItemPosition::ACCESSORY_POSITION_PETS)->first() @endphp
                            <span class="ak-linker" data-hasqtip="linker_item_{{$item->ItemId}}"><img src="{{DofusForge::item($item->Template->IconId, 52)}}"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_{{$item->ItemId}}","linker-path":"{{URL::route('linker.get', 'item')}}","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"{{$item->ItemId}}","character":"{{$character->Id}}","server":"{{$server}}","position":"right","builder":""}}</script>                       
                            @endif                  
                        </div>
                    </div>
                    </div>
                    <div class="ak-equipment-bottom">
                    <div class="ak-equipment-item ak-equipment-dofus" id="ak-dofus-character-equipment-item-dofus">
                            @if($itemsbottom->containsStrict('Position', \App\ItemPosition::INVENTORY_POSITION_DOFUS_1))
                            @php $item = $itemsbottom->where('Position', \App\ItemPosition::INVENTORY_POSITION_DOFUS_1)->first() @endphp
                            <span class="ak-linker" data-hasqtip="linker_item_{{$item->ItemId}}"><img src="{{DofusForge::item($item->Template->IconId, 52)}}"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_{{$item->ItemId}}","linker-path":"{{URL::route('linker.get', 'item')}}","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"{{$item->ItemId}}","character":"{{$character->Id}}","server":"{{$server}}","position":"bottom","builder":""}}</script>                       
                            @endif               
                    </div>
                    <div class="ak-equipment-item ak-equipment-dofus" id="ak-dofus-character-equipment-item-dofus">
                            @if($itemsbottom->containsStrict('Position', \App\ItemPosition::INVENTORY_POSITION_DOFUS_2))
                            @php $item = $itemsbottom->where('Position', \App\ItemPosition::INVENTORY_POSITION_DOFUS_2)->first() @endphp
                            <span class="ak-linker" data-hasqtip="linker_item_{{$item->ItemId}}"><img src="{{DofusForge::item($item->Template->IconId, 52)}}"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_{{$item->ItemId}}","linker-path":"{{URL::route('linker.get', 'item')}}","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"{{$item->ItemId}}","character":"{{$character->Id}}","server":"{{$server}}","position":"bottom","builder":""}}</script>                      
                            @endif             
                    </div>
                    <div class="ak-equipment-item ak-equipment-dofus" id="ak-dofus-character-equipment-item-dofus">
                            @if($itemsbottom->containsStrict('Position', \App\ItemPosition::INVENTORY_POSITION_DOFUS_3))
                            @php $item = $itemsbottom->where('Position', \App\ItemPosition::INVENTORY_POSITION_DOFUS_3)->first() @endphp
                            <span class="ak-linker" data-hasqtip="linker_item_{{$item->ItemId}}"><img src="{{DofusForge::item($item->Template->IconId, 52)}}"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_{{$item->ItemId}}","linker-path":"{{URL::route('linker.get', 'item')}}","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"{{$item->ItemId}}","character":"{{$character->Id}}","server":"{{$server}}","position":"bottom","builder":""}}</script>                       
                            @endif           
                    </div>
                    <div class="ak-equipment-item ak-equipment-dofus" id="ak-dofus-character-equipment-item-dofus">
                            @if($itemsbottom->containsStrict('Position', \App\ItemPosition::INVENTORY_POSITION_DOFUS_4))
                            @php $item = $itemsbottom->where('Position', \App\ItemPosition::INVENTORY_POSITION_DOFUS_4)->first() @endphp
                            <span class="ak-linker" data-hasqtip="linker_item_{{$item->ItemId}}"><img src="{{DofusForge::item($item->Template->IconId, 52)}}"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_{{$item->ItemId}}","linker-path":"{{URL::route('linker.get', 'item')}}","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"{{$item->ItemId}}","character":"{{$character->Id}}","server":"{{$server}}","position":"bottom","builder":""}}</script>                       
                            @endif            
                    </div>
                    <div class="ak-equipment-item ak-equipment-dofus" id="ak-dofus-character-equipment-item-dofus">
                            @if($itemsbottom->containsStrict('Position', \App\ItemPosition::INVENTORY_POSITION_DOFUS_5))
                            @php $item = $itemsbottom->where('Position', \App\ItemPosition::INVENTORY_POSITION_DOFUS_5)->first() @endphp
                            <span class="ak-linker" data-hasqtip="linker_item_{{$item->ItemId}}"><img src="{{DofusForge::item($item->Template->IconId, 52)}}"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_{{$item->ItemId}}","linker-path":"{{URL::route('linker.get', 'item')}}","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"{{$item->ItemId}}","character":"{{$character->Id}}","server":"{{$server}}","position":"bottom","builder":""}}</script>                        
                            @endif              
                    </div>
                    <div class="ak-equipment-item ak-equipment-dofus" id="ak-dofus-character-equipment-item-dofus">
                            @if($itemsbottom->containsStrict('Position', \App\ItemPosition::INVENTORY_POSITION_DOFUS_6))
                            @php $item = $itemsbottom->where('Position', \App\ItemPosition::INVENTORY_POSITION_DOFUS_6)->first() @endphp
                            <span class="ak-linker" data-hasqtip="linker_item_{{$item->ItemId}}"><img src="{{DofusForge::item($item->Template->IconId, 52)}}"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_{{$item->ItemId}}","linker-path":"{{URL::route('linker.get', 'item')}}","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"{{$item->ItemId}}","character":"{{$character->Id}}","server":"{{$server}}","position":"bottom","builder":""}}</script>                       
                            @endif             
                    </div>
                    </div>
                    <div class="ak-equipment-bottom">
                    <div class="ak-equipment-item ak-equipment-idol" id="ak-dofus-character-equipment-item-idol">
                    </div>
                    <div class="ak-equipment-item ak-equipment-idol" id="ak-dofus-character-equipment-item-idol">
                    </div>
                    <div class="ak-equipment-item ak-equipment-idol" id="ak-dofus-character-equipment-item-idol">
                    </div>
                    <div class="ak-equipment-item ak-equipment-idol" id="ak-dofus-character-equipment-item-idol">
                    </div>
                    <div class="ak-equipment-item ak-equipment-idol" id="ak-dofus-character-equipment-item-idol">
                    </div>
                    <div class="ak-equipment-item ak-equipment-idol" id="ak-dofus-character-equipment-item-idol">
                    </div>
                    </div>
                </div>
                <div class="ak-equipment-mobile visible-xs">
                    <div class="ak-equipment-middle">
                    <div class="ak-character-equipments">
                        <div class="ak-chracter-illustration">
                            <div class="ak-set-illu">
                                <div class="ak-entitylook" alt="" style="background:url({{ DofusForge::player($character, $server, 'full', 1, 270, 361, 10) }}) top left;width:270px;height:361px"></div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="ak-equipment-list">
                    <div class="row">
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-shield" id="ak-dofus-character-equipment-item-shield">
                                <span class="ak-linker"><img src="http://staticns.ankama.com/dofus/ng/img/../../../dofus/www/game/items/52/82072.png"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_13240","linker-path":"\/fr\/linker\/item","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"13240","character":"3207405","server":"4003","builder":""}}</script>            
                            </div>
                        </div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-amulet" id="ak-dofus-character-equipment-item-amulet">
                                <span class="ak-linker"><img src="http://staticns.ankama.com/dofus/ng/img/../../../dofus/www/game/items/52/1278.png"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_17581","linker-path":"\/fr\/linker\/item","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"17581","character":"3207405","server":"4003","builder":""}}</script>            
                            </div>
                        </div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-ring1" id="ak-dofus-character-equipment-item-ring1">
                                <span class="ak-linker"><img src="http://staticns.ankama.com/dofus/ng/img/../../../dofus/www/game/items/52/9314.png"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_17582","linker-path":"\/fr\/linker\/item","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"17582","character":"3207405","server":"4003","builder":""}}</script>            
                            </div>
                        </div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-cap" id="ak-dofus-character-equipment-item-cap">
                                <span class="ak-linker"><img src="http://staticns.ankama.com/dofus/ng/img/../../../dofus/www/game/items/52/17371.png"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_18008","linker-path":"\/fr\/linker\/item","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"18008","character":"3207405","server":"4003","builder":""}}</script>            
                            </div>
                        </div>
                        <div class="ak-row-break clearfix visible-xs visible-sm visible-md visible-lg "></div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-boots" id="ak-dofus-character-equipment-item-boots">
                                <span class="ak-linker"><img src="http://staticns.ankama.com/dofus/ng/img/../../../dofus/www/game/items/52/11315.png"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_18007","linker-path":"\/fr\/linker\/item","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"18007","character":"3207405","server":"4003","builder":""}}</script>            
                            </div>
                        </div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-weapon" id="ak-dofus-character-equipment-item-weapon">
                                <span class="ak-linker"><img src="http://staticns.ankama.com/dofus/ng/img/../../../dofus/www/game/items/52/19076.png"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_18014","linker-path":"\/fr\/linker\/item","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"18014","character":"3207405","server":"4003","builder":""}}</script>            
                            </div>
                        </div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-hat" id="ak-dofus-character-equipment-item-hat">
                                <span class="ak-linker"><img src="http://staticns.ankama.com/dofus/ng/img/../../../dofus/www/game/items/52/16328.png"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_13123","linker-path":"\/fr\/linker\/item","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"13123","character":"3207405","server":"4003","builder":""}}</script>            
                            </div>
                        </div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-ring2" id="ak-dofus-character-equipment-item-ring2">
                                <span class="ak-linker"><img src="http://staticns.ankama.com/dofus/ng/img/../../../dofus/www/game/items/52/9229.png"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_12113","linker-path":"\/fr\/linker\/item","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"12113","character":"3207405","server":"4003","builder":""}}</script>            
                            </div>
                        </div>
                        <div class="ak-row-break clearfix visible-xs visible-sm visible-md visible-lg "></div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-belt" id="ak-dofus-character-equipment-item-belt">
                                <span class="ak-linker"><img src="http://staticns.ankama.com/dofus/ng/img/../../../dofus/www/game/items/52/10314.png"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_18009","linker-path":"\/fr\/linker\/item","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"18009","character":"3207405","server":"4003","builder":""}}</script>            
                            </div>
                        </div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-pet" id="ak-dofus-character-equipment-item-pet">
                                <span class="ak-linker"><img src="http://staticns.ankama.com/dofus/ng/img/../../../dofus/www/game/items/52/121002.png"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_13182","linker-path":"\/fr\/linker\/item","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"13182","character":"3207405","server":"4003","builder":""}}</script>            
                            </div>
                        </div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-dofus" id="ak-dofus-character-equipment-item-dofus">
                                <span class="ak-linker"><img src="http://staticns.ankama.com/dofus/ng/img/../../../dofus/www/game/items/52/151257.png"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_16335","linker-path":"\/fr\/linker\/item","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"16335","character":"3207405","server":"4003","builder":""}}</script>            
                            </div>
                        </div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-dofus" id="ak-dofus-character-equipment-item-dofus">
                                <span class="ak-linker"><img src="http://staticns.ankama.com/dofus/ng/img/../../../dofus/www/game/items/52/151180.png"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_16245","linker-path":"\/fr\/linker\/item","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"16245","character":"3207405","server":"4003","builder":""}}</script>            
                            </div>
                        </div>
                        <div class="ak-row-break clearfix visible-xs visible-sm visible-md visible-lg "></div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-dofus" id="ak-dofus-character-equipment-item-dofus">
                                <span class="ak-linker"><img src="http://staticns.ankama.com/dofus/ng/img/../../../dofus/www/game/items/52/151222.png"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_16267","linker-path":"\/fr\/linker\/item","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"16267","character":"3207405","server":"4003","builder":""}}</script>            
                            </div>
                        </div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-dofus" id="ak-dofus-character-equipment-item-dofus">
                                <span class="ak-linker"><img src="http://staticns.ankama.com/dofus/ng/img/../../../dofus/www/game/items/52/151254.png"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_16333","linker-path":"\/fr\/linker\/item","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"16333","character":"3207405","server":"4003","builder":""}}</script>            
                            </div>
                        </div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-dofus" id="ak-dofus-character-equipment-item-dofus">
                                <span class="ak-linker"><img src="http://staticns.ankama.com/dofus/ng/img/../../../dofus/www/game/items/52/151243.png"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_16329","linker-path":"\/fr\/linker\/item","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"16329","character":"3207405","server":"4003","builder":""}}</script>            
                            </div>
                        </div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-dofus" id="ak-dofus-character-equipment-item-dofus">
                                <span class="ak-linker"><img src="http://staticns.ankama.com/dofus/ng/img/../../../dofus/www/game/items/52/151183.png"></span><script type="application/json">{"iShowDelay":250,"iHideDelay":250,"linker-id":"linker_item_16193","linker-path":"\/fr\/linker\/item","linker-display-type":"TOOLTIP","linker-query-datas":{"l":"fr","id":"16193","character":"3207405","server":"4003","builder":""}}</script>            
                            </div>
                        </div>
                        <div class="ak-row-break clearfix visible-xs visible-sm visible-md visible-lg "></div>
                    </div>
                    </div>
                    <div class="ak-equipment-list">
                    <div class="row">
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-idol" id="ak-dofus-character-equipment-item-idol">
                            </div>
                        </div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-idol" id="ak-dofus-character-equipment-item-idol">
                            </div>
                        </div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-idol" id="ak-dofus-character-equipment-item-idol">
                            </div>
                        </div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-idol" id="ak-dofus-character-equipment-item-idol">
                            </div>
                        </div>
                        <div class="ak-row-break clearfix visible-xs visible-sm visible-md visible-lg "></div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-idol" id="ak-dofus-character-equipment-item-idol">
                            </div>
                        </div>
                        <div class="col-xs-3 ">
                            <div class="ak-equipment-item ak-equipment-idol" id="ak-dofus-character-equipment-item-idol">
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ak-container ak-panel ak-caracteristics-spells-content">
            <h2 class="ak-panel-title">
                <span class="ak-panel-title-icon"></span>
                Sorts    
            </h2>
            <div class="ak-panel-content">
            @if(config('dofus.details')[$server]->version == 2.10)
            <p class="alert alert-info">En raison de la version du serveur ({{config('dofus.details')[$server]->version}}), il est possible que certaines icônes ne correspondent pas.</p>
            @endif
                <div class="ak-spells-list">
                    <a class="ak-anchor" id="spell-details"></a>
                    <div>
                        <div class="ak-spell-list-row">
                        @foreach($spells as $spell)
                            <div class="ak-list-block ak-spell ak-spell-selected">
                                <span class="ak-spell-nb">{{$spell->Level}}</span>
                                <img src="{{$spell->template($server)->image('55')}}" alt="{{$spell->template($server)->name()}}">
                                <script type="application/json">{"pjax":{"push":false}}</script>
                                <span class="ak-tooltip hidden" style="display: none;">{{$spell->template($server)->name()}}</span>
                            </div>
                        @endforeach 
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>




    </div>
</div>
@stop
@section('bottom')
    {!! Html::script('js/common2.js') !!}
@endsection