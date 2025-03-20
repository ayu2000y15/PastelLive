@extends('layouts.app')

@section('title', 'お問い合わせ内容の確認')

@section('content')
    <div class="container">
        <h1 class="page-title">お問い合わせ内容の確認</h1>

        <div class="step-indicator">
            <div class="step completed" data-title="入力">1</div>
            <div class="step active" data-title="確認">2</div>
            <div class="step" data-title="完了">3</div>
        </div>

        <div class="contact-form">
            <h2>入力内容をご確認ください</h2>

            <div>
                <table>
                    <tbody>
                        @foreach($formFields as $field)
                            @if(isset($formData[$field['col_name']]))
                                <tr>
                                    <th>{{ $field['view_name'] }}</th>
                                    <td>
                                        @if($field['type'] === 'textarea')
                                            {!! nl2br(e($formData[$field['col_name']])) !!}
                                        @elseif($field['type'] === 'select' && isset($field['options']))
                                            @foreach($field['options'] as $option)
                                                @if($option['value'] == $formData[$field['col_name']])
                                                    {{ $option['label'] }}
                                                @endif
                                            @endforeach
                                        @else
                                            {{ $formData[$field['col_name']] }}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div>
                        <button type="submit" onclick="history.back();" name="back" value="1">
                            入力画面に戻る
                        </button>
                        <button type="submit">
                            送信する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection