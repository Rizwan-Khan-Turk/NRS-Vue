@extends('emails.layout')

@section('content')
    <tr>
        <td class="content-cell">
            <div class="f-fallback">
                <h1>Welcome, <br> {{ $email }}!</h1>
                <!-- Action -->
                <p>For reference, here's your login information:</p>
                <table class="attributes" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td class="attributes_content">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="attributes_item">
                                        <span class="f-fallback">
                                            <strong>Username:</strong> {{ $email }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="attributes_item">
                                        <span class="f-fallback">
                                            <strong>Password:</strong> {{ $password }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
@endsection
