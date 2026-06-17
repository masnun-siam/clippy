<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $clip->slug }}</title>
<style>html,body{margin:0;height:100%}iframe{border:0;width:100%;height:100%;display:block}</style>
</head><body>
<iframe sandbox="allow-scripts allow-forms allow-popups" srcdoc="{{ $clip->html }}"></iframe>
</body></html>
