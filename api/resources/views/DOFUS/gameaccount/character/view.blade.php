@extends('layouts.contents.default')
@include('layouts.menus.base')
@section('header')
    {!! Html::style('css/directories.css') !!}
    {!! Html::style('css/builders.css') !!}
@stop
@section('breadcrumbs')
{? $page_name = 'Personnage' ?}
{!! Breadcrumbs::render('home') !!}
@stop
@section('beta')
    <div class="ak-beta"></div>
@endsection
@section('content')
<div class="ak-container ak-main-center">
   <div class="ak-title-container ak-backlink">
      <h1><span class="ak-icon-big ak-character"></span></a>{{$character->Name}}</h1>
      <a href="" class="ak-backlink-button">Retour</a>
   </div>
   <div class="ak-page-menu">
      <nav class="ak-nav-expand ">
         <div class="ak-nav-expand-container">
            <ul class="ak-nav-links ak-nav-expand-links">
               <li class="on"><a href="{{route('characters.view', [$server, $character->Id, $character->Name])}}">Profil</a></li>
               <li><a href="{{route('characters.caracteristics', [$server, $character->Id, $character->Name])}}">Caractéristiques</a></li>
            </ul>
         </div>
      </nav>
   </div>
   <div class="ak-container ak-panel ak-nocontentpadding">
      <div class="ak-panel-content">
         <div class="ak-character-banner">
            <div class="ak-character-picture">
               <div class="ak-entitylook" alt="" style="background:url({{ DofusForge::player($character, $server, 'full', 1, 150, 220, 10) }}) top left;width:150px;height:220px">
               </div>
            </div>
            @if($character->ornamentActive($server))
            <a class="ak-character-picture-show on"></a>
            <a class="ak-character-ornament-show"></a>
            <div class="ak-character-ornament ornament-{{$character->ornamentActive($server)->AssetId}}">
               <span>{{$character->Name}}</span>
            </div>
            @endif
         </div>
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
                <div class="ak-directories-property ak-directories-property-creation-date">
                    <span class="ak-directories-creation-date">Création : Le {{$character->CreationDate->format('d/m/Y')}}</span>
                </div>
            </div>
         </div>
      </div>
   </div>
   <div class="row ak-container ak-directories">
      <div class="ak-column ak-container col-md-4 ak-directories-left">
         <div class="ak-container ak-panel-stack ak-glue">
             @if($character->guild())
            <div class="ak-container ak-panel ak-belong-guild">
               <div class="ak-panel-title">
                  <span class="ak-panel-title-icon"></span>
                  Appartient à la guilde
               </div>
               <div class="ak-panel-content">
                  <div class="ak-character-illu">
                     <div class="ak-emblem" style="background:url({{ URL::asset($character->guild()->emblem(110,110)) }}) center center;width:110px;height:110px">
                     </div>
                  </div>
                  <a href="" class="ak-infos-guildname">{{$character->guild($server)->Name}}</a><br>
                  <span class="ak-infos-guildlevel">Niveau {{$character->guild($server)->level()}}</span> - <span class="ak-infos-guildmembers">{{count($character->guild($server)->members($server))}} membres</span>
               </div>
            </div>
            @endif
            <div class="ak-container ak-panel large ak-infos-alignement ak-{{$character->alignment()->sideFrench()}}">
                <div class="ak-panel-title">
                    <span class="ak-panel-title-icon"></span>Alignement
                </div>
                <div class="ak-panel-content">
                    <div class="ak-alignment ak-alignment-{{$character->alignment()->sideFrench()}}" style="@if($character->AlignmentSide != 0)background:url({{ URL::asset('imgs/assets/characters/wings/wings_'.$character->alignment()->sideFrench().'_'.$character->alignment()->level().'.png')}})@else background:url({{ URL::asset('imgs/assets/characters/wings/neutre.png')}})@endif 0 center no-repeat;">
                        <span class="ak-alignment-name">{{$character->alignment()->sideFrench()}} @if(!empty($character->alignment()->level()))- GRADE {{$character->alignment()->level()}}@endif</span>
                    </div>
                </div>
            </div>
         </div>
      </div>
      <div class="ak-column ak-container col-md-8 ak-directories-right">
         <div class="ak-container ak-panel-stack ak-directories ak-glue">
            <div class="ak-container ak-panel ak-character-presentation">
                <div class="ak-panel-title">
                    <span class="ak-panel-title-icon"></span>Présentation
                    <span class="ak-presentation-date">Dernière mise à jour le 04/03/2017</span>            
                </div>
                <div class="ak-panel-content">Ma présentation ici</div>
            </div>
            <div class="ak-container ak-panel">
               <div class="ak-panel-title">
                  <span class="ak-panel-title-icon"></span>Dernière connexion            
               </div>
               <div class="ak-panel-content">
                  <div class="ak-character-actions">
                     <div class="ak-actions">
                        <div class="ak-actions-list">
                           <div class="ak-container ak-content-list ">
                              <div class="ak-list-element">
                                 <div class="ak-main">
                                    <div class="ak-main-content ">
                                       <div class="ak-content">
                                          <div class="ak-title">{{$character->Name}}. Dernière connexion : {{$character->LastUsage->diffForHumans()}}                            </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="ak-container ak-panel ak-nocontentpadding ak-character-ranking">
                <div class="ak-panel-title">
                    <span class="ak-panel-title-icon"></span>
                    Statistiques            
                </div>
                <div class="ak-panel-content">
                    <div class="ak-total-xp">Total XP : <span>{{ Utils::format_price($character->Experience, ' ') }}</span></div>
                    <div class="ak-total-xp">Prestige : <span>{{ $character->PrestigeRank }}</span></div>
                    <div class="ak-total-honor">Points d'Honneur : <span>{{ Utils::format_price($character->Honor, ' ') }}</span></div>
                    <div class="ak-responsivetable-wrapper" style="overflow: hidden;">
                        <table border="1" class="ak-container ak-table ak-responsivetable" style="white-space: nowrap; visibility: visible;">
                            <thead>
                            <tr>
                                <th>Classement</th>
                                <th>Xp</th>
                                <th>PvP</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="ak-bg-odd">
                                <td class="ak-first-col">Général</td>
                                <td>{{$character->position('xp', 'all', $server)}}</td>
                                <td>{{$character->position('pvp', 'all', $server)}}</td>
                            </tr>
                            <tr class="ak-bg-odd">
                                <td class="ak-first-col">Parmis tous les ({{$character->classe()}})</td>
                                <td>{{$character->position('xp', 'breed', $server)}}</td>
                                <td>{{$character->position('pvp', 'breed', $server)}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="ak-total-kolizeum">Cote Kolizeum : <span>{{$character->ArenaRank}}</span></div>
                    <div class="ak-total-kolizeum-max">Cote Kolizeum Maximale : <span>{{$character->ArenaMaxRank}}</span></div>
                    <div class="ak-responsivetable-wrapper" style="overflow: hidden;">
                        <table border="1" class="ak-container ak-table ak-responsivetable" style="white-space: nowrap; visibility: visible;">
                            <thead>
                            <tr>
                                <th>Kolizeum</th>
                                <th>Nombre</th>
                                <th>Gagnés</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="ak-bg-odd">
                                <td class="ak-first-col">Matchs du jour</td>
                                <td>{{$character->ArenaDailyMatchsCount}}</td>
                                <td>{{$character->ArenaDailyMatchsWon}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
         </div>
      </div>
   </div>
</div>
@stop
