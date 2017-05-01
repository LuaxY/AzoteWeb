@extends('layouts.contents.default')
@include('layouts.menus.shop')

@section('header')
    {!! Html::style('css/encyclopedia.css') !!}
    {!! Html::style('css/directories.css') !!}
@stop

@section('breadcrumbs')
{? $page_name = 'Marché des personnages' ?}
{!! Breadcrumbs::render('shop.page', $page_name) !!}
@stop

@section('content')
<div id="ak-filters-move-target" class="ak-filters-move-target"></div><a id="list" class="ak-anchor"></a>
<div class="ak-title-container">
    <h1><span class="ak-icon-big ak-shop"></span> Marché des personnages</h1>
</div>
<div class="ak-page-menu ak-glue">
   <nav class="ak-nav-expand ak-expanded">
      <div class="ak-nav-expand-container">
         <ul class="ak-nav-links ak-nav-expand-links">
            <li>
               <a href="{{URL::route('shop.index')}}">
               Accueil      </a>
            </li>
            <li>
               <a href="{{URL::route('shop.payment.country')}}">
               Ogrines      </a>
            </li>
            <li class="on">
               <a href="{{URL::route('shop.market')}}">
               Marché des personnages      </a>
            </li>
         </ul>
      </div>
      <div class="ak-nav-expand-more">
         <span class="ak-nav-expand-icon ak-picto-common ak-picto-open" style="display: none;">+</span>
      </div>
   </nav>
</div>
<div class="ak-container ak-panel ak-nocontentpadding">
    @if($market_characters->total() == 0)
        <div class="ak-panel-content">
                <div class="ak-list-options ak-listoptions-actions ak-ajaxloader" data-target="div.main">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="ak-list-info ak-list-info-no-item">
                            <img src="{{URL::asset('imgs/assets/no-result.png')}}" class="img-max-responsive">
                            <div><br>
                                <strong>Aucun</strong> élément ne correspond à vos critères.<br>
                                Pour apparaitre dans le marché des personnages, le joueur doit avoir mis le personnage en vente<br>
                                et attendre 30 minutes pour que la liste soit mise à jour. 
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="ak-list-options">
                    <div class="ak-list-info ak-list-info-no-item">
                        <strong>0</strong> éléments correspondent à vos critères  
                    </div>
                </div>
        </div>
    @else
   <div class="ak-panel-content">
      <div class="ak-list-options ak-listoptions-actions ak-ajaxloader" data-target="div.main">
         <div class="row">
            <div class="col-sm-12">
               <div class="ak-list-info" style="text-align:center;">
                @if($market_characters->hasMorePages())
                    @if($market_characters->total() > 20)
                    Plus de <strong>200</strong> résultats ont été trouvé. Veuillez affiner votre recherche en utilisant les filtres : Nom de personnage, Classe, Niveau et Sexe
                    @else
                    <strong>{{$market_characters->total()}}</strong> résultats ont été trouvé. Vous pouvez affiner votre recherche en utilisant les filtres : Nom de personnage, Classe, Niveau et Sexe
                    @endif
                @else
                    @if($market_characters->total() == 1)
                     <strong>{{$market_characters->total()}}</strong> résultat a été trouvé.
                    @else
                    <strong>{{$market_characters->total()}}</strong> résultats ont été trouvé.
                    @endif
                @endif
                </div>
            </div>
         </div>
         <div class="clearfix"></div>
      </div>
      <div class="ak-responsivetable-wrapper" style="overflow: hidden;">
         <table border="1" class="ak-table ak-responsivetable" style="white-space: nowrap; visibility: visible;">
            <thead>
               <tr>
                  <th data-priority="9"></th>
                  <th data-priority="8">Nom</th>
                  <th data-priority="7">Classe<span class="ak-picto-common"></span></th>
                  <th data-priority="6">Niveau<span class="ak-picto-common"></span></th>
                  <th data-priority="5">Prestige<span class="ak-picto-common"></span></th>                  
                  <th data-priority="4">Sexe<span class="ak-picto-common"></span></th>
                  <th data-priority="3">Serveur<span class="ak-picto-common"></span></th>
                  <th data-priority="2">Prix<span class="ak-picto-common"></span></th>
                  <th data-priority="1">Actions<span class="ak-picto-common"></span></th>
               </tr>
            </thead>
            <tbody>
                @foreach($market_characters as $market)
                <tr class="ak-bg-odd">
                    <td class="ak-ladder-avatar ak-valign-top">
                        <div class="ak-entitylook" alt="{{$market->Name}}" style="background:url({{DofusForge::player($market->character(), $market->character()->server, 'face', 1, 48, 48)}}) top left;width:48px;height:48px"></div>
                    </td>
                    <td><a target="_blank" href="{{ URL::route('characters.view',[$market->character()->server, $market->character_id, $market->character()->Name])}}">{{$market->character()->Name}}</a></td>
                    <td>{{$market->character()->classe()}}</td>
                    <td>{{$market->character()->level()}}</td>
                    <td>{{$market->character()->PrestigeRank}}</td>
                    <td>{{$market->character()->sex()}}</td>
                    <td>{{ucfirst($market->character()->server)}}</td>
                    <td>{{Utils::format_price($market->ogrines)}} <span class="ak-icon-small ak-ogrines-icon"></span></td>
                    <td>
                    <a target="_blank" href="{{ URL::route('characters.view',[$market->character()->server, $market->character_id, $market->character()->Name])}}"><span class="ak-icon-tiny ak-filter ak-tooltip" title="Consulter"></span></a>
                    <a href=""><span class="ak-icon-tiny ak-cart ak-tooltip" title="Acheter"></span></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
         </table>
      </div>
   </div>
   <div class="ak-panel-footer">
        @if($market_characters->links())
            <div class="text-center ak-pagination">
                <nav>
                    {{ $market_characters->appends(Request::capture()->except('page'))->links('pagination.default', ['target' => 'div.main', 'settings' => '{"scroll":true}']) }}
                </nav>
            </div>
        @endif
   </div>
   @endif
</div>
@stop