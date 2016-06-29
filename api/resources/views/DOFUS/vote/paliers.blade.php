                        <div class="vote-palier-name">Palier : {{ $palierId }}</div>
                            <div class="vote-progress">
                                <div class="progress-bar" data="{{ $progress }}"></div>
                            </div>
                            <div class="vote-time-line">
@foreach ($steps as $i => $step)
                                <div class="vote-reward vote-block-{{ $i }}" item="{{ $step->itemId }}" step="{{ $i }}" votes="{{ $step->votes }}">
                                    <span class="arrow"></span>
                                    <div class="vote-reward-step @if ($current == $i) selected @endif">
                                        <span class="vote-reward-text">
                                            <span>{{ $step->votes }}</span>
                                            votes
                                        </span>
                                        <span class="vote-reward-icon"></span>
                                    </div>
                                </div>
@endforeach
                            </div>
                            <div class="vote-item mask-relative masked">
                                <div class="vote-gift-details vote-block-{{ $current }}">
                                    <div class="vote-gift-title-block">
                                        <span class="vote-reward-text">
                                            <span></span>
                                            votes
                                        </span>
                                        <div class="vote-gift-title">
                                            <span class="vote-gift-title-next">Cadeau à obtenir :</span>
                                            <span class="vote-gift-title-object"></span>
                                        </div>
                                    </div>
                                    <div class="vote-gift-description-block">
                                        <div class="object-illu left">
                                            <img src="" />
                                        </div>
                                        <div class="vote-gift-description right">
                                            <div class="title">
                                                <span class="picto"></span>
                                                Description
                                            </div>
                                            <p></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="loadmask" style="display: block;"></div>
                                <div class="loading" style="display: block; top: 100px;"></div>
                            </div>
                            <div id="load-item" item="{{ $steps[$current]->itemId }}" step="{{ $current }}" votes="{{ $steps[$current]->votes }}"></div>
