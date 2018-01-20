<?php
    header('Content-Type: application/xhtml+xml; charset=utf-8');

    if (!defined('root')) {
		define('root', '../../');
	}

    require_once root.'debug/test2/lib/start_ses.php';

    if (isset($_SESSION['un'])) {
        $un = $_SESSION['un'];//un is unique
    }

    echo '<?xml version="1.0" encoding="utf-8"?>';
?>


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <meta charset="utf-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="mobile-web-app-capable" content="yes"/>
		<meta name="author" content="Desmond" />
		<meta name="copyright" content="Copyright &#169; 2009-2109 Minglun Zhu. All rights reserved." />
		<meta name="generator" content="Atom" />

		<!--<link rel="shortcut icon" type="image/x-icon" href="/shared/imgs/favicon.ico" />-->
		<link rel="stylesheet" type="text/css" href="/debug/test2/csss/default.css"/>
        <link rel="stylesheet" type="text/css" href="/debug/test2/csss/css.css"/>

        <script src="/debug/test2/jss/cmm.js">
		</script>
        <script src="/debug/test2/jss/js.js">
		</script>

		<title>AutoTrader</title>
	</head>

	<body class="pos-abs-ful of-auto <?php
        if (isset($un)) {
            echo 'logged-in';
        }
    ?>">
        <header class="dsp-flx w-100 h-100">
            <section class="dsp-flx">
                <h1>
                    Auto Trader
                </h1>
            </section>

            <section class="dsp-flx">
                <div class="form-cnr pos-rel">
                    <form class="pos-rel" action="<?php
                        if (isset($un)) {
                            echo 'logout.php';
                        } else {
                            echo 'login.php';
                        }
                    ?>">
                        <h2 class="va-mid">
                            <?php
                                if (isset($un)) {
                                    echo $un;
                                } else {
                                    echo 'Login';
                                }
                            ?>
                        </h2>

                        <input name="un" type="text" placeholder="Username" required="required"/><!--html validations can be bypassed, and should not be relied on against hackers-->
                        <input name="pw" type="password" placeholder="Password" required="required"/>

                        <button type="submit" class="va-mid">
                            <?php
                                if (isset($un)) {
                                    echo 'Log Out';
                                } else {
                                    echo 'Log In';
                                }
                            ?>
                        </button>

                        <p>
                        </p>
                    </form>
                </div>
            </section>
        </header>

        <section class="ctt-cnr">
            <h2>
                Welcome
            </h2>

            <p class="tagline">
                Find new &amp; used cars for sale in South Africa
            </p>

            <p>
                [...shows loading, ...loading done, ...shows content]
            </p>

            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam malesuada ultricies leo vitae consequat. Sed elementum mi tincidunt, porttitor felis vel, pellentesque ante. In vitae lorem facilisis, vulputate turpis vehicula, commodo justo. Aenean dictum diam at pulvinar rutrum. Nunc feugiat elit lacus, vitae bibendum sapien ultricies non. Nunc porta maximus viverra. Etiam fringilla consectetur laoreet. Ut eget aliquet magna, ornare venenatis nunc. Pellentesque ac mi eget odio mollis pellentesque in eget purus. Quisque vel feugiat turpis, quis porta mauris. Nam quis rutrum nunc, eget mollis dolor.

            Donec id ex id metus auctor fringilla. Nam suscipit tellus eget dignissim ultrices. Ut eu pharetra sapien. Aliquam suscipit enim mauris. Etiam sem odio, vehicula ultrices ligula luctus, blandit eleifend dui. Integer lobortis, mauris eu consequat pulvinar, ante leo vulputate urna, eu sollicitudin lectus turpis pellentesque ex. Etiam sed nulla justo. Morbi sed fringilla sapien.

            Proin nulla dolor, faucibus sit amet orci ac, egestas accumsan turpis. Nam nulla diam, vehicula at metus eu, lobortis egestas magna. Praesent eu aliquam quam. Proin eu lectus ut mi facilisis eleifend. Suspendisse scelerisque faucibus dui, id consectetur mi laoreet a. Donec dignissim, turpis non lobortis egestas, nibh leo dignissim enim, sed vulputate sem odio sed urna. Donec nulla tortor, tristique a efficitur ut, convallis quis elit.

            Aliquam erat volutpat. Maecenas id pretium diam. Morbi porttitor feugiat odio, sit amet consequat mauris condimentum ut. In dignissim feugiat nisl, eu posuere nisi cursus vehicula. Quisque sed ligula nec odio imperdiet eleifend eu commodo purus. Sed ornare condimentum commodo. Phasellus et pulvinar massa. Aliquam erat volutpat. Donec a nunc pharetra, lobortis tortor sit amet, congue lacus. Vivamus laoreet, est ut euismod auctor, arcu odio laoreet ante, nec euismod nisi orci non justo. Vestibulum blandit tincidunt erat, quis rutrum mi facilisis a.

            Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec non ex efficitur, sollicitudin lacus nec, semper dui. Integer congue, nibh ut consequat tristique, metus justo tempus tortor, sed posuere erat sapien sit amet libero. Quisque libero odio, fringilla ut auctor eu, aliquam at sem. Morbi tempus eu nisi in mollis. Nam eu semper turpis. Nunc ac dignissim nisl, nec luctus arcu. Donec vulputate sollicitudin augue, at eleifend enim pulvinar non. In varius maximus nibh ut suscipit. Sed nunc sapien, ultricies non imperdiet venenatis, interdum ut ligula. Quisque posuere tempus leo, laoreet placerat libero blandit ac.

            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam malesuada ultricies leo vitae consequat. Sed elementum mi tincidunt, porttitor felis vel, pellentesque ante. In vitae lorem facilisis, vulputate turpis vehicula, commodo justo. Aenean dictum diam at pulvinar rutrum. Nunc feugiat elit lacus, vitae bibendum sapien ultricies non. Nunc porta maximus viverra. Etiam fringilla consectetur laoreet. Ut eget aliquet magna, ornare venenatis nunc. Pellentesque ac mi eget odio mollis pellentesque in eget purus. Quisque vel feugiat turpis, quis porta mauris. Nam quis rutrum nunc, eget mollis dolor.

            Donec id ex id metus auctor fringilla. Nam suscipit tellus eget dignissim ultrices. Ut eu pharetra sapien. Aliquam suscipit enim mauris. Etiam sem odio, vehicula ultrices ligula luctus, blandit eleifend dui. Integer lobortis, mauris eu consequat pulvinar, ante leo vulputate urna, eu sollicitudin lectus turpis pellentesque ex. Etiam sed nulla justo. Morbi sed fringilla sapien.

            Proin nulla dolor, faucibus sit amet orci ac, egestas accumsan turpis. Nam nulla diam, vehicula at metus eu, lobortis egestas magna. Praesent eu aliquam quam. Proin eu lectus ut mi facilisis eleifend. Suspendisse scelerisque faucibus dui, id consectetur mi laoreet a. Donec dignissim, turpis non lobortis egestas, nibh leo dignissim enim, sed vulputate sem odio sed urna. Donec nulla tortor, tristique a efficitur ut, convallis quis elit.

            Aliquam erat volutpat. Maecenas id pretium diam. Morbi porttitor feugiat odio, sit amet consequat mauris condimentum ut. In dignissim feugiat nisl, eu posuere nisi cursus vehicula. Quisque sed ligula nec odio imperdiet eleifend eu commodo purus. Sed ornare condimentum commodo. Phasellus et pulvinar massa. Aliquam erat volutpat. Donec a nunc pharetra, lobortis tortor sit amet, congue lacus. Vivamus laoreet, est ut euismod auctor, arcu odio laoreet ante, nec euismod nisi orci non justo. Vestibulum blandit tincidunt erat, quis rutrum mi facilisis a.

            Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec non ex efficitur, sollicitudin lacus nec, semper dui. Integer congue, nibh ut consequat tristique, metus justo tempus tortor, sed posuere erat sapien sit amet libero. Quisque libero odio, fringilla ut auctor eu, aliquam at sem. Morbi tempus eu nisi in mollis. Nam eu semper turpis. Nunc ac dignissim nisl, nec luctus arcu. Donec vulputate sollicitudin augue, at eleifend enim pulvinar non. In varius maximus nibh ut suscipit. Sed nunc sapien, ultricies non imperdiet venenatis, interdum ut ligula. Quisque posuere tempus leo, laoreet placerat libero blandit ac.

            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam malesuada ultricies leo vitae consequat. Sed elementum mi tincidunt, porttitor felis vel, pellentesque ante. In vitae lorem facilisis, vulputate turpis vehicula, commodo justo. Aenean dictum diam at pulvinar rutrum. Nunc feugiat elit lacus, vitae bibendum sapien ultricies non. Nunc porta maximus viverra. Etiam fringilla consectetur laoreet. Ut eget aliquet magna, ornare venenatis nunc. Pellentesque ac mi eget odio mollis pellentesque in eget purus. Quisque vel feugiat turpis, quis porta mauris. Nam quis rutrum nunc, eget mollis dolor.

            Donec id ex id metus auctor fringilla. Nam suscipit tellus eget dignissim ultrices. Ut eu pharetra sapien. Aliquam suscipit enim mauris. Etiam sem odio, vehicula ultrices ligula luctus, blandit eleifend dui. Integer lobortis, mauris eu consequat pulvinar, ante leo vulputate urna, eu sollicitudin lectus turpis pellentesque ex. Etiam sed nulla justo. Morbi sed fringilla sapien.

            Proin nulla dolor, faucibus sit amet orci ac, egestas accumsan turpis. Nam nulla diam, vehicula at metus eu, lobortis egestas magna. Praesent eu aliquam quam. Proin eu lectus ut mi facilisis eleifend. Suspendisse scelerisque faucibus dui, id consectetur mi laoreet a. Donec dignissim, turpis non lobortis egestas, nibh leo dignissim enim, sed vulputate sem odio sed urna. Donec nulla tortor, tristique a efficitur ut, convallis quis elit.

            Aliquam erat volutpat. Maecenas id pretium diam. Morbi porttitor feugiat odio, sit amet consequat mauris condimentum ut. In dignissim feugiat nisl, eu posuere nisi cursus vehicula. Quisque sed ligula nec odio imperdiet eleifend eu commodo purus. Sed ornare condimentum commodo. Phasellus et pulvinar massa. Aliquam erat volutpat. Donec a nunc pharetra, lobortis tortor sit amet, congue lacus. Vivamus laoreet, est ut euismod auctor, arcu odio laoreet ante, nec euismod nisi orci non justo. Vestibulum blandit tincidunt erat, quis rutrum mi facilisis a.

            Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec non ex efficitur, sollicitudin lacus nec, semper dui. Integer congue, nibh ut consequat tristique, metus justo tempus tortor, sed posuere erat sapien sit amet libero. Quisque libero odio, fringilla ut auctor eu, aliquam at sem. Morbi tempus eu nisi in mollis. Nam eu semper turpis. Nunc ac dignissim nisl, nec luctus arcu. Donec vulputate sollicitudin augue, at eleifend enim pulvinar non. In varius maximus nibh ut suscipit. Sed nunc sapien, ultricies non imperdiet venenatis, interdum ut ligula. Quisque posuere tempus leo, laoreet placerat libero blandit ac.

            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam malesuada ultricies leo vitae consequat. Sed elementum mi tincidunt, porttitor felis vel, pellentesque ante. In vitae lorem facilisis, vulputate turpis vehicula, commodo justo. Aenean dictum diam at pulvinar rutrum. Nunc feugiat elit lacus, vitae bibendum sapien ultricies non. Nunc porta maximus viverra. Etiam fringilla consectetur laoreet. Ut eget aliquet magna, ornare venenatis nunc. Pellentesque ac mi eget odio mollis pellentesque in eget purus. Quisque vel feugiat turpis, quis porta mauris. Nam quis rutrum nunc, eget mollis dolor.

            Donec id ex id metus auctor fringilla. Nam suscipit tellus eget dignissim ultrices. Ut eu pharetra sapien. Aliquam suscipit enim mauris. Etiam sem odio, vehicula ultrices ligula luctus, blandit eleifend dui. Integer lobortis, mauris eu consequat pulvinar, ante leo vulputate urna, eu sollicitudin lectus turpis pellentesque ex. Etiam sed nulla justo. Morbi sed fringilla sapien.

            Proin nulla dolor, faucibus sit amet orci ac, egestas accumsan turpis. Nam nulla diam, vehicula at metus eu, lobortis egestas magna. Praesent eu aliquam quam. Proin eu lectus ut mi facilisis eleifend. Suspendisse scelerisque faucibus dui, id consectetur mi laoreet a. Donec dignissim, turpis non lobortis egestas, nibh leo dignissim enim, sed vulputate sem odio sed urna. Donec nulla tortor, tristique a efficitur ut, convallis quis elit.

            Aliquam erat volutpat. Maecenas id pretium diam. Morbi porttitor feugiat odio, sit amet consequat mauris condimentum ut. In dignissim feugiat nisl, eu posuere nisi cursus vehicula. Quisque sed ligula nec odio imperdiet eleifend eu commodo purus. Sed ornare condimentum commodo. Phasellus et pulvinar massa. Aliquam erat volutpat. Donec a nunc pharetra, lobortis tortor sit amet, congue lacus. Vivamus laoreet, est ut euismod auctor, arcu odio laoreet ante, nec euismod nisi orci non justo. Vestibulum blandit tincidunt erat, quis rutrum mi facilisis a.

            Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec non ex efficitur, sollicitudin lacus nec, semper dui. Integer congue, nibh ut consequat tristique, metus justo tempus tortor, sed posuere erat sapien sit amet libero. Quisque libero odio, fringilla ut auctor eu, aliquam at sem. Morbi tempus eu nisi in mollis. Nam eu semper turpis. Nunc ac dignissim nisl, nec luctus arcu. Donec vulputate sollicitudin augue, at eleifend enim pulvinar non. In varius maximus nibh ut suscipit. Sed nunc sapien, ultricies non imperdiet venenatis, interdum ut ligula. Quisque posuere tempus leo, laoreet placerat libero blandit ac.

            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam malesuada ultricies leo vitae consequat. Sed elementum mi tincidunt, porttitor felis vel, pellentesque ante. In vitae lorem facilisis, vulputate turpis vehicula, commodo justo. Aenean dictum diam at pulvinar rutrum. Nunc feugiat elit lacus, vitae bibendum sapien ultricies non. Nunc porta maximus viverra. Etiam fringilla consectetur laoreet. Ut eget aliquet magna, ornare venenatis nunc. Pellentesque ac mi eget odio mollis pellentesque in eget purus. Quisque vel feugiat turpis, quis porta mauris. Nam quis rutrum nunc, eget mollis dolor.

            Donec id ex id metus auctor fringilla. Nam suscipit tellus eget dignissim ultrices. Ut eu pharetra sapien. Aliquam suscipit enim mauris. Etiam sem odio, vehicula ultrices ligula luctus, blandit eleifend dui. Integer lobortis, mauris eu consequat pulvinar, ante leo vulputate urna, eu sollicitudin lectus turpis pellentesque ex. Etiam sed nulla justo. Morbi sed fringilla sapien.

            Proin nulla dolor, faucibus sit amet orci ac, egestas accumsan turpis. Nam nulla diam, vehicula at metus eu, lobortis egestas magna. Praesent eu aliquam quam. Proin eu lectus ut mi facilisis eleifend. Suspendisse scelerisque faucibus dui, id consectetur mi laoreet a. Donec dignissim, turpis non lobortis egestas, nibh leo dignissim enim, sed vulputate sem odio sed urna. Donec nulla tortor, tristique a efficitur ut, convallis quis elit.

            Aliquam erat volutpat. Maecenas id pretium diam. Morbi porttitor feugiat odio, sit amet consequat mauris condimentum ut. In dignissim feugiat nisl, eu posuere nisi cursus vehicula. Quisque sed ligula nec odio imperdiet eleifend eu commodo purus. Sed ornare condimentum commodo. Phasellus et pulvinar massa. Aliquam erat volutpat. Donec a nunc pharetra, lobortis tortor sit amet, congue lacus. Vivamus laoreet, est ut euismod auctor, arcu odio laoreet ante, nec euismod nisi orci non justo. Vestibulum blandit tincidunt erat, quis rutrum mi facilisis a.

            Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec non ex efficitur, sollicitudin lacus nec, semper dui. Integer congue, nibh ut consequat tristique, metus justo tempus tortor, sed posuere erat sapien sit amet libero. Quisque libero odio, fringilla ut auctor eu, aliquam at sem. Morbi tempus eu nisi in mollis. Nam eu semper turpis. Nunc ac dignissim nisl, nec luctus arcu. Donec vulputate sollicitudin augue, at eleifend enim pulvinar non. In varius maximus nibh ut suscipit. Sed nunc sapien, ultricies non imperdiet venenatis, interdum ut ligula. Quisque posuere tempus leo, laoreet placerat libero blandit ac.

            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam malesuada ultricies leo vitae consequat. Sed elementum mi tincidunt, porttitor felis vel, pellentesque ante. In vitae lorem facilisis, vulputate turpis vehicula, commodo justo. Aenean dictum diam at pulvinar rutrum. Nunc feugiat elit lacus, vitae bibendum sapien ultricies non. Nunc porta maximus viverra. Etiam fringilla consectetur laoreet. Ut eget aliquet magna, ornare venenatis nunc. Pellentesque ac mi eget odio mollis pellentesque in eget purus. Quisque vel feugiat turpis, quis porta mauris. Nam quis rutrum nunc, eget mollis dolor.

            Donec id ex id metus auctor fringilla. Nam suscipit tellus eget dignissim ultrices. Ut eu pharetra sapien. Aliquam suscipit enim mauris. Etiam sem odio, vehicula ultrices ligula luctus, blandit eleifend dui. Integer lobortis, mauris eu consequat pulvinar, ante leo vulputate urna, eu sollicitudin lectus turpis pellentesque ex. Etiam sed nulla justo. Morbi sed fringilla sapien.

            Proin nulla dolor, faucibus sit amet orci ac, egestas accumsan turpis. Nam nulla diam, vehicula at metus eu, lobortis egestas magna. Praesent eu aliquam quam. Proin eu lectus ut mi facilisis eleifend. Suspendisse scelerisque faucibus dui, id consectetur mi laoreet a. Donec dignissim, turpis non lobortis egestas, nibh leo dignissim enim, sed vulputate sem odio sed urna. Donec nulla tortor, tristique a efficitur ut, convallis quis elit.

            Aliquam erat volutpat. Maecenas id pretium diam. Morbi porttitor feugiat odio, sit amet consequat mauris condimentum ut. In dignissim feugiat nisl, eu posuere nisi cursus vehicula. Quisque sed ligula nec odio imperdiet eleifend eu commodo purus. Sed ornare condimentum commodo. Phasellus et pulvinar massa. Aliquam erat volutpat. Donec a nunc pharetra, lobortis tortor sit amet, congue lacus. Vivamus laoreet, est ut euismod auctor, arcu odio laoreet ante, nec euismod nisi orci non justo. Vestibulum blandit tincidunt erat, quis rutrum mi facilisis a.

            Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec non ex efficitur, sollicitudin lacus nec, semper dui. Integer congue, nibh ut consequat tristique, metus justo tempus tortor, sed posuere erat sapien sit amet libero. Quisque libero odio, fringilla ut auctor eu, aliquam at sem. Morbi tempus eu nisi in mollis. Nam eu semper turpis. Nunc ac dignissim nisl, nec luctus arcu. Donec vulputate sollicitudin augue, at eleifend enim pulvinar non. In varius maximus nibh ut suscipit. Sed nunc sapien, ultricies non imperdiet venenatis, interdum ut ligula. Quisque posuere tempus leo, laoreet placerat libero blandit ac.

            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam malesuada ultricies leo vitae consequat. Sed elementum mi tincidunt, porttitor felis vel, pellentesque ante. In vitae lorem facilisis, vulputate turpis vehicula, commodo justo. Aenean dictum diam at pulvinar rutrum. Nunc feugiat elit lacus, vitae bibendum sapien ultricies non. Nunc porta maximus viverra. Etiam fringilla consectetur laoreet. Ut eget aliquet magna, ornare venenatis nunc. Pellentesque ac mi eget odio mollis pellentesque in eget purus. Quisque vel feugiat turpis, quis porta mauris. Nam quis rutrum nunc, eget mollis dolor.

            Donec id ex id metus auctor fringilla. Nam suscipit tellus eget dignissim ultrices. Ut eu pharetra sapien. Aliquam suscipit enim mauris. Etiam sem odio, vehicula ultrices ligula luctus, blandit eleifend dui. Integer lobortis, mauris eu consequat pulvinar, ante leo vulputate urna, eu sollicitudin lectus turpis pellentesque ex. Etiam sed nulla justo. Morbi sed fringilla sapien.

            Proin nulla dolor, faucibus sit amet orci ac, egestas accumsan turpis. Nam nulla diam, vehicula at metus eu, lobortis egestas magna. Praesent eu aliquam quam. Proin eu lectus ut mi facilisis eleifend. Suspendisse scelerisque faucibus dui, id consectetur mi laoreet a. Donec dignissim, turpis non lobortis egestas, nibh leo dignissim enim, sed vulputate sem odio sed urna. Donec nulla tortor, tristique a efficitur ut, convallis quis elit.

            Aliquam erat volutpat. Maecenas id pretium diam. Morbi porttitor feugiat odio, sit amet consequat mauris condimentum ut. In dignissim feugiat nisl, eu posuere nisi cursus vehicula. Quisque sed ligula nec odio imperdiet eleifend eu commodo purus. Sed ornare condimentum commodo. Phasellus et pulvinar massa. Aliquam erat volutpat. Donec a nunc pharetra, lobortis tortor sit amet, congue lacus. Vivamus laoreet, est ut euismod auctor, arcu odio laoreet ante, nec euismod nisi orci non justo. Vestibulum blandit tincidunt erat, quis rutrum mi facilisis a.

            Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec non ex efficitur, sollicitudin lacus nec, semper dui. Integer congue, nibh ut consequat tristique, metus justo tempus tortor, sed posuere erat sapien sit amet libero. Quisque libero odio, fringilla ut auctor eu, aliquam at sem. Morbi tempus eu nisi in mollis. Nam eu semper turpis. Nunc ac dignissim nisl, nec luctus arcu. Donec vulputate sollicitudin augue, at eleifend enim pulvinar non. In varius maximus nibh ut suscipit. Sed nunc sapien, ultricies non imperdiet venenatis, interdum ut ligula. Quisque posuere tempus leo, laoreet placerat libero blandit ac.

            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam malesuada ultricies leo vitae consequat. Sed elementum mi tincidunt, porttitor felis vel, pellentesque ante. In vitae lorem facilisis, vulputate turpis vehicula, commodo justo. Aenean dictum diam at pulvinar rutrum. Nunc feugiat elit lacus, vitae bibendum sapien ultricies non. Nunc porta maximus viverra. Etiam fringilla consectetur laoreet. Ut eget aliquet magna, ornare venenatis nunc. Pellentesque ac mi eget odio mollis pellentesque in eget purus. Quisque vel feugiat turpis, quis porta mauris. Nam quis rutrum nunc, eget mollis dolor.

            Donec id ex id metus auctor fringilla. Nam suscipit tellus eget dignissim ultrices. Ut eu pharetra sapien. Aliquam suscipit enim mauris. Etiam sem odio, vehicula ultrices ligula luctus, blandit eleifend dui. Integer lobortis, mauris eu consequat pulvinar, ante leo vulputate urna, eu sollicitudin lectus turpis pellentesque ex. Etiam sed nulla justo. Morbi sed fringilla sapien.

            Proin nulla dolor, faucibus sit amet orci ac, egestas accumsan turpis. Nam nulla diam, vehicula at metus eu, lobortis egestas magna. Praesent eu aliquam quam. Proin eu lectus ut mi facilisis eleifend. Suspendisse scelerisque faucibus dui, id consectetur mi laoreet a. Donec dignissim, turpis non lobortis egestas, nibh leo dignissim enim, sed vulputate sem odio sed urna. Donec nulla tortor, tristique a efficitur ut, convallis quis elit.

            Aliquam erat volutpat. Maecenas id pretium diam. Morbi porttitor feugiat odio, sit amet consequat mauris condimentum ut. In dignissim feugiat nisl, eu posuere nisi cursus vehicula. Quisque sed ligula nec odio imperdiet eleifend eu commodo purus. Sed ornare condimentum commodo. Phasellus et pulvinar massa. Aliquam erat volutpat. Donec a nunc pharetra, lobortis tortor sit amet, congue lacus. Vivamus laoreet, est ut euismod auctor, arcu odio laoreet ante, nec euismod nisi orci non justo. Vestibulum blandit tincidunt erat, quis rutrum mi facilisis a.

            Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec non ex efficitur, sollicitudin lacus nec, semper dui. Integer congue, nibh ut consequat tristique, metus justo tempus tortor, sed posuere erat sapien sit amet libero. Quisque libero odio, fringilla ut auctor eu, aliquam at sem. Morbi tempus eu nisi in mollis. Nam eu semper turpis. Nunc ac dignissim nisl, nec luctus arcu. Donec vulputate sollicitudin augue, at eleifend enim pulvinar non. In varius maximus nibh ut suscipit. Sed nunc sapien, ultricies non imperdiet venenatis, interdum ut ligula. Quisque posuere tempus leo, laoreet placerat libero blandit ac.
        </section>

        <div class="msg-cnr pos-fxd">
        </div>
	</body>
</html>
