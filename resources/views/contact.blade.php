@extends('layouts.app')

@section('title', 'CONTACT')

@section('content')
    <div class="container">
        {{-- <h1 class="page-title">CONTACT</h1> --}}
        {{-- <img src="{{ asset($titleContact->file_path . $titleContact->file_name) }}" alt="タイトル" class="title-image"> --}}

        <div class="step-indicator">
            <div class="step active" data-title="入力">1</div>
            <div class="step" data-title="確認">2</div>
            <div class="step" data-title="完了">3</div>
        </div>
        <div class="contact-form1">
            <form action="{{ route('contact.confirm') }}" method="POST" >
                @csrf
                @foreach($formFields as $field)
                    <div class="form-row">
                        <label for="{{ $field['col_name'] }}" class="form-label">
                            {{ $field['view_name'] }}
                            @if(isset($field['required_flg']) && $field['required_flg'] === '1')
                                <span class="required">*</span>
                            @endif
                        </label>
                        <div class="input-wrapper">
                        @switch($field['type'])
                            @case('textarea')
                                <textarea
                                    id="{{ $field['col_name'] }}"
                                    name="{{ $field['col_name'] }}"
                                    class="form-control @error($field['col_name']) is-invalid @enderror"
                                    rows="5"
                                    @if(isset($field['required_flg']) && $field['required_flg'] === '1') required @endif
                                >{{ old($field['col_name']) }}</textarea>
                                @break

                            @case('select')
                                <select
                                    id="{{ $field['col_name'] }}"
                                    name="{{ $field['col_name'] }}"
                                    class="form-select @error($field['col_name']) is-invalid @enderror"
                                    @if(isset($field['required_flg']) && $field['required_flg'] === '1') required @endif
                                >
                                    <option value="">選択してください</option>
                                    @if(isset($field['options']) && is_array($field['options']))
                                        @foreach($field['options'] as $option)
                                            <option value="{{ $option['value'] }}" {{ old($field['col_name']) == $option['value'] ? 'selected' : '' }}>
                                                {{ $option['label'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @break

                            @default
                                <input
                                    type="{{ $field['type'] }}"
                                    id="{{ $field['col_name'] }}"
                                    name="{{ $field['col_name'] }}"
                                    class="form-control @error($field['col_name']) is-invalid @enderror"
                                    value="{{ old($field['col_name']) }}"
                                    @if(isset($field['required_flg']) && $field['required_flg'] === '1') required @endif
                                >
                        @endswitch
                        </div>
                        @error($field['col_name'])
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    @if (!$loop->last)
                        <div class="line-area">
                            <hr class="form-line">
                        </div>
                    @endif
                @endforeach
                <div class="form-row checkbox-row">
                    <div class="checkbox-wrapper">
                        {{-- <input type="checkbox" id="privacy_policy" name="privacy_policy" required> --}}
                        <label for="privacy_policy" style="color: #666;">
                            入力された個人情報の取り扱いについて、プライバシーポリシーに基づき取り扱われることに同意するものとします。
                        </label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="button-wrapper">
                        <button type="submit" class="btn submit-button">
                            <img class="btn-img" src="{{ asset($contactBtn->file_path . $contactBtn->file_name) }}"
                                alt="Button Image">
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="contact-notes">
            <p>ご意見や個人的なご質問、誹謗中傷に関するご報告、営業等に関しましては、返信できない可能性がございます。</p>
            <p>所属クリエイターや協力会社に情報を共有させていただく場合がございますので予めご了承ください。</p>
        </div>
    </div>
@endsection

