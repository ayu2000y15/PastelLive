@extends('layouts.app')

@section('title', 'TALENT')

@section('content')
    <div class="container talent">
        <h1 class="page-title">TALENT</h1>
        <div class="button-wrapper">
            <button type="button" id="female-btn" class="btn talent active">
                <img class="btn-img" src="{{ asset($femaleBtn->file_path . $femaleBtn->file_name) }}"
                    alt="{{ $femaleBtn->alt }}}}">
            </button>
            <button type="button" id="male-btn" class="btn talent">
                <img class="btn-img" src="{{ asset($maleBtn->file_path . $maleBtn->file_name) }}"
                    alt="{{ $maleBtn->alt }}}}">
            </button>
        </div>
        <!-- 女性 -->
        <div class="female active">
            <div class="talent-grid">
                @foreach($talents as $talent)
                    @if($talent->gender_flg == "female")
                        <form action="{{ route('talent.show') }}" name="form_{{ $talent->id }}" method="POST">
                            @csrf
                            <input type="hidden" name="talent_id" value="{{ $talent->id }}">
                            <button type="submit" class="talent-card-button">
                                <div class="talent-card">
                                    <img src="{{ asset($talent->talent_topimage) }}" alt="タレント画像" class="talent-image">
                                    <div class="talent-name-main">{{ $talent->talent_name }}</div>
                                </div>
                            </button>
                        </form>
                    @endif
                @endforeach
            </div>
        </div>
        <!-- 男性 -->
        <div class="male" style="display: none">
            <div class="talent-grid">
                @foreach($talents as $talent)
                    @if($talent->gender_flg == "male")
                        <form action="{{ route('talent.show') }}" name="form_{{ $talent->id }}" method="POST">
                            @csrf
                            <input type="hidden" name="talent_id" value="{{ $talent->id }}">
                            <button type="submit" class="talent-card-button">
                                <div class="talent-card">
                                    <img src="{{ asset($talent->talent_topimage) }}" alt="タレント画像" class="talent-image">
                                    <div class="talent-name-main">{{ $talent->talent_name }}</div>
                                </div>
                            </button>
                        </form>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const femaleBtn = document.getElementById('female-btn');
            const maleBtn = document.getElementById('male-btn');
            const femaleSection = document.querySelector('.female');
            const maleSection = document.querySelector('.male');

            function setActiveButton(activeBtn, inactiveBtn, activeSection, inactiveSection) {
                activeBtn.classList.add('active');
                activeBtn.classList.remove('inactive');
                activeBtn.disabled = true;
                inactiveBtn.classList.remove('active');
                inactiveBtn.classList.add('inactive');
                inactiveBtn.disabled = false;
                activeSection.classList.add('active');
                activeSection.style.display = 'block';
                inactiveSection.classList.remove('active');
                inactiveSection.style.display = 'none';
            }

            femaleBtn.addEventListener('click', function () {
                setActiveButton(femaleBtn, maleBtn, femaleSection, maleSection);
            });

            maleBtn.addEventListener('click', function () {
                setActiveButton(maleBtn, femaleBtn, maleSection, femaleSection);
            });

            // Set default state to female
            setActiveButton(femaleBtn, maleBtn, femaleSection, maleSection);
        });
    </script>
@endsection