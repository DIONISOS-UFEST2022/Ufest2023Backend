@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'UFEST2023')
                <img src="https://lh3.googleusercontent.com/XXHei-CSMPG8sgMOJ3x_3Y93mRggcdVemF_5b11vajfe6tkgzaph4tX32xqxwYsxoH0=w2400"
                    class="logo" alt="">
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>
