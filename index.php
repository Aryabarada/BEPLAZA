<?php
// get json file
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://cdn01.rumahweb.com/under-construction/panduan.json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$links = json_decode(curl_exec($ch));
curl_close($ch);
?>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Selamat, website <?php echo $_SERVER['HTTP_HOST'];?> telah aktif!</title>
<meta name="description" content="plazabarber sudah mendunia. Segera buat website dan email <?php echo $_SERVER['HTTP_HOST'];?> dan mulai perjalanan bisnis Anda.">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;800&display=swap" rel="stylesheet">
<link href="https://cdn01.rumahweb.com/under-construction/style.css" rel="stylesheet">
</head>
<body>
        
        <section class="hero">
                <div style="background-image:url('https://cdn01.rumahweb.com/under-construction/img/dot.png');background-repeat:no-repeat;margin: -60px 0;padding: 60px 0;background-position: 30px 50px;">
                        <div class="container">
                                <div class="row">
                                        <div class="col-md-7">
                                                <p class="top1">Gerbang Anda untuk mendunia telah terbuka</p>
                                                <p class="top2"><b style="color: #FFC546;"><?php echo ucfirst($_SERVER['HTTP_HOST']);?></b><br>sudah aktif</p>
                                                <p class="top3">di develop oleh. <b>#internal</b> <b>#tugasMPTI</b> <b>#jangan_dihack_ya_puh....sepuh....</b></p>
                                        </div>
                                        <div class="col-md-5"><img src="https://cdn01.rumahweb.com/under-construction/img/hero.png" alt="<?php echo ucfirst($_SERVER['HTTP_HOST']);?> sudah aktif" width="400"></div>
                                </div>
                        </div>
                </div>
        </section>
        <section class="steps" style="padding: 80px 0;">
                
        </section>
       
</div>
</body>
    
</html>