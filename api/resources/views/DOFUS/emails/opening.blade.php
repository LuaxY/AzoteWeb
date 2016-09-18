<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Newsletter d'Azote</title>
    <style>

        body {
            background-color: #212121;
            color: #fff;
        }

        body, td {
            margin: 0;
            padding: 0;
        }

        table {
            -webkit-border-horizontal-spacing: 0;
            -webkit-border-vertical-spacing: 0;
        }

        .welcome,
        .details .image,
        .footer {
            font-family: 'Trebuchet MS', Helvetica, Arial, 'sans-serif';
        }

        .top {
            height: 350px;
        }

        .play {
            height: 80px;
        }

        .play tr td {
            width: 250px;
            height: 80px;
        }

        .play .btn-play a,
        .play .btn-play img {
            cursor: pointer;
        }

        .welcome {
            height: 150px;
            font-size: 17px;
            line-height: 1.4;
        }

        .details .border {
            width: 48px;
        }

        .details .spacer {
            width: 48px;
        }

        .details .image {
            width: 185px;
        }

        .details .image img {
            width: 185px;
            height: 185px;
        }

        .details .image p {
            text-align: justify;
        }

        .footer {
            line-height: 28px;
            text-align: center;
        }

        .footer table {
            width: 100%;
        }

        .footer img {
            display: inline-block;
            vertical-align: middle;
        }

        .footer span {
            display: inline-block;
            padding-top: 10px;
        }

    </style>
</head>
<body>
    <center>
        <table width="750">
            <tr><td class="top"><img src="{{ URL::asset('imgs/email/top.jpg') }}" /></td></tr>
            <tr>
                <td class="play">
                    <table>
                        <tr>
                            <td></td>
                            <td class="btn-play">
                                <center><a href="{{ route('register') }}"><img src="{{ URL::asset('imgs/email/play.jpg') }}" /></a></center>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="welcome">
                    <center>
                        Azote est désormais ouvert et jouable gratuitement !<br>
                        Plus de 1500 monstres, 100 zones de jeu et énormément de donjons vous attendent !<br>
                        <br>
                        Venez vite découvrir Azote !
                    </center>
                </td>
            </tr>
            <tr>
                <td class="details">
                    <table>
                        <tr>
                            <td class="border"></td>
                            <td class="image">
                                <table>
                                    <tr>
                                        <td>
                                            <img src="{{ URL::asset('imgs/email/economy.jpg') }}" />
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="spacer"></td>
                            <td class="image">
                                <table>
                                    <tr>
                                        <td>
                                            <img src="{{ URL::asset('imgs/email/team.jpg') }}" />
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="spacer"></td>
                            <td class="image">
                                <table>
                                    <tr>
                                        <td>
                                            <img src="{{ URL::asset('imgs/email/shop.jpg') }}" />
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="border"></td>
                        </tr>
                        <tr>
                            <td class="border"></td>
                            <td class="image">
                                <table>
                                    <tr>
                                        <td>
                                            <h3>UNE RÉELLE ÉCONOMIE</h3>
                                            <p>L'économie d'Azote a été pensée dans un simple but, vous proposer une durée de vie des plus grandes. Vous ne pourrez jamais vous ennuyer sur Azote, le but étant de perfectionner votre équipement et d'avoir un maximum de kamas !</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="spacer"></td>
                            <td class="image">
                                <table>
                                    <tr>
                                        <td>
                                            <h3>UNE ÉQUIPE COMPÉTENTE</h3>
                                            <p>Avec un forum, un site et des serveurs développés pour être toujours plus proche de la communauté, notre équipe saura répondre à toutes vos attentes. Un système de live-support est aussi disponible afin de régler vos problèmes.</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="spacer"></td>
                            <td class="image">
                                <table>
                                    <tr>
                                        <td>
                                            <h3>PAS DE “PAY TO WIN”</h3>
                                            <p>Notre gameplay a été pensé de façon à ce que les joueurs qui payent en boutique n'aient aucun avantage de plus que ceux qui décideront de ne pas payer. Par ailleurs nous ne proposons pas d'objets exclusifs aux statistiques boostées.</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="border"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    <table>
                        <tr>
                            <td><a href="{{ route('home') }}"><img border="0" height="28" src="{{ URL::asset('imgs/azote_text.png') }}"></a> <span>&copy; {{ date('Y') }}. Tous droits réservés.</span></td>
                        </tr>
                        <tr><td><br></td></tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
