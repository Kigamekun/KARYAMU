<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Template</title>
</head>

<body style="background-color: #f4f4f4; margin: 0; padding: 0;">

    <table cellpadding="0" cellspacing="0" border="0" width="100%"
        style="background-color: #f4f4f4; padding: 20px 0;">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" border="0" width="600" align="center"
                    style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0px 0px 10px rgba(0,0,0,0.1);">
                    <tr>
                        <td align="center" style="padding: 20px;">
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 10px 20px;">
                            <h1 style="font-family: Arial, sans-serif; font-weight: 500; color: #333333; margin: 0;">
                                KaryaMu</h1>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 10px 20px;">
                            <h3 style="font-family: Arial, sans-serif; font-weight: 400; color: #333333; margin: 0;">
                                Karya baru telah terupload mohon cek dashboard karyamu untuk approve karya tersebut.
                            </h3>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 20px;">
                            @if ($data->type == 'image')
                                <img src="{{ env('APP_URL') . '/storage/artwork/' . $data->file_path }}"
                                    alt="Thumbnail"
                                    style="width: 240px; height: auto; border-radius: 10px; display: block;">
                            @else
                                <img src="https://img.youtube.com/vi/{{ $data->video_id }}/hqdefault.jpg"
                                    alt="Thumbnail"
                                    style="width: 240px; height: auto; border-radius: 10px; display: block;">
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 10px 20px;">
                            <h3
                                style="font-family: Arial, sans-serif; font-weight: 400; color: #333333; margin: 0; display:inline-block;">
                                Karya</h3>
                            <h3
                                style="font-family: Arial, sans-serif; font-weight: 500; color: #333333; margin: 0; display:inline-block;">
                                "{{ $data->title }}"</h3>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 20px;">
                            <a href="{{ env('APP_URL') . '/karya' }}"
                                style="display: inline-block; padding: 10px 20px; background-color: #0097FF; color: white; font-family: Arial, sans-serif; font-weight: 500; text-decoration: none; border-radius: 5px;">Lihat
                                Karya</a>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="padding: 20px; text-align: center; font-family: Arial, sans-serif; font-size: 12px; color: #666666;">
                            &copy; 2024 KaryaMu. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>
