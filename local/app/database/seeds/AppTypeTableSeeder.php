<?php

class AppTypeTableSeeder extends Seeder {

    public function run()
    {
        DB::table('app_types')->delete();

        \Mobile\Model\AppType::create(array(
            'sort' => 10,
            'name' => 'business',
            'icon' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 48 48" style="enable-background:new 0 0 48 48;" xml:space="preserve">
<style type="text/css">
	.st0{fill:none;stroke:#010101;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
</style>
<g>
	<polyline class="st0" points="5,20 5,47 43,47 43,20 	"/>
	<polyline class="st0" points="33,46 33,29 15,29 15,46 	"/>
	<path class="st0" d="M4.3,19.4c2.2,0,4.1-1.8,4.1-4c0,2.2,1.9,4,4.1,4s4-1.8,4-4c0,2.2,1.8,4,4,4s4-1.8,4-4c0,2.2,1.8,4,4,4
		s4-1.8,4-4c0,2.2,1.8,4,4,4c2.2,0,4-1.8,4-4c0,2.2,1,4,3.2,4s3.2-1.8,3.2-4v-1L42.6,1H6.5L1,14.4v1C1,17.6,2.6,19.4,4.3,19.4z"/>
</g>
</svg>',
			'icon_width' => 44,
			'app_icon' => 'store'
        ));

        \Mobile\Model\AppType::create(array(
            'sort' => 20,
            'name' => 'music',
            'icon' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 48 48" style="enable-background:new 0 0 48 48;" xml:space="preserve">
<style type="text/css">
	.st0{fill:none;stroke:#010101;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
</style>
<g>
	<path class="st0" d="M47,41c0,3.3-2.7,6-6,6H7c-3.3,0-6-2.7-6-6V7c0-3.3,2.7-6,6-6h34c3.3,0,6,2.7,6,6V41z"/>
	<circle class="st0" cx="9.4" cy="14.6" r="3"/>
	<circle class="st0" cx="23.4" cy="18.6" r="3"/>
	<circle class="st0" cx="37.4" cy="10.6" r="3"/>
	<line class="st0" x1="9" y1="18" x2="9" y2="28"/>
	<line class="st0" x1="9" y1="6" x2="9" y2="11"/>
	<line class="st0" x1="23" y1="6" x2="23" y2="15"/>
	<line class="st0" x1="23" y1="22" x2="23" y2="28"/>
	<line class="st0" x1="37" y1="14" x2="37" y2="28"/>
	<line class="st0" x1="37" y1="6" x2="37" y2="7"/>
	<g>
		<circle class="st0" cx="9.4" cy="38.6" r="4.9"/>
		<line class="st0" x1="15" y1="42.9" x2="10.2" y2="39.2"/>
	</g>
	<g>
		<circle class="st0" cx="23.4" cy="38.6" r="4.9"/>
		<line class="st0" x1="17.8" y1="34.5" x2="22.6" y2="38"/>
	</g>
	<g>
		<circle class="st0" cx="37.4" cy="38.6" r="4.9"/>
		<line class="st0" x1="43.9" y1="35.9" x2="38.4" y2="38.2"/>
	</g>
</g>
</svg>',
			'icon_width' => 44,
			'app_icon' => 'mixpanel'
        ));

        \Mobile\Model\AppType::create(array(
            'sort' => 30,
            'name' => 'events',
            'icon' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 48 47" style="enable-background:new 0 0 48 47;" xml:space="preserve">
<style type="text/css">
	.st0{fill:none;stroke:#010101;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
	.st1{fill:none;stroke:#010101;stroke-width:2;stroke-linecap:round;stroke-miterlimit:10;}
</style>
<g>
	<line class="st0" x1="13" y1="1" x2="13" y2="7"/>
	<line class="st0" x1="35" y1="1" x2="35" y2="7"/>
	<line class="st0" x1="8" y1="12" x2="40" y2="12"/>
	<line class="st0" x1="8" y1="18" x2="14" y2="18"/>
	<line class="st0" x1="20" y1="18" x2="28" y2="18"/>
	<line class="st0" x1="34" y1="18" x2="40" y2="18"/>
	<line class="st0" x1="8" y1="24" x2="14" y2="24"/>
	<line class="st0" x1="20" y1="24" x2="28" y2="24"/>
	<line class="st0" x1="34" y1="24" x2="40" y2="24"/>
	<line class="st0" x1="8" y1="30" x2="14" y2="30"/>
	<line class="st0" x1="20" y1="30" x2="28" y2="30"/>
	<line class="st0" x1="34" y1="30" x2="40" y2="30"/>
	<line class="st0" x1="8" y1="36" x2="14" y2="36"/>
	<line class="st0" x1="20" y1="36" x2="28" y2="36"/>
	<line class="st0" x1="34" y1="36" x2="40" y2="36"/>
	<path class="st1" d="M47,44c0,1.1-0.9,2-2,2H3c-1.1,0-2-0.9-2-2V6c0-1.1,0.9-2,2-2h42c1.1,0,2,0.9,2,2V44z"/>
</g>
</svg>',
			'icon_width' => 45,
			'app_icon' => 'calendar'
        ));

        \Mobile\Model\AppType::create(array(
            'sort' => 40,
            'name' => 'restaurants',
            'icon' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 68 47" style="enable-background:new 0 0 68 47;" xml:space="preserve">
<style type="text/css">
	.st1{fill:none;stroke:#010101;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
</style>
<g>
	<g>
		<g>
			<path class="st0 stroke-0" d="M32.4,2.6c11,0,20,9,20,20s-9,20-20,20c-11,0-20-9-20-20S21.4,2.6,32.4,2.6 M32.4,0.6c-12.2,0-22,9.9-22,22
				c0,12.2,9.9,22,22,22c12.2,0,22-9.8,22-22C54.4,10.4,44.5,0.6,32.4,0.6L32.4,0.6z"/>
		</g>
		<g>
			<path class="st0 stroke-0" d="M32.4,9.9c7,0,12.7,5.7,12.7,12.7c0,7-5.7,12.7-12.7,12.7c-7,0-12.7-5.7-12.7-12.7
				C19.7,15.6,25.4,9.9,32.4,9.9 M32.4,7.9c-8.1,0-14.7,6.6-14.7,14.7s6.6,14.7,14.7,14.7c8.1,0,14.7-6.6,14.7-14.7
				S40.5,7.9,32.4,7.9L32.4,7.9z"/>
		</g>
	</g>
	<g>
		<line class="st1" x1="7" y1="45" x2="7" y2="5"/>
		<path class="st1" d="M7,9V3.6C7,2.1,5.5,1,4,1C2.5,1,1,2.3,1,3.8V21"/>
		<path class="st1" d="M7,21.8c0,1.5-1.4,2.8-2.9,2.8c-1.5,0-3.1-1.4-3.1-2.9V19"/>
	</g>
	<g>
		<line class="st1" x1="61" y1="45" x2="61" y2="1"/>
		<path class="st1" d="M67,1v7.4c0,3.8-2.2,6.8-6,6.8c-3.8,0-6-3.1-6-6.8V1"/>
	</g>
</g>
</svg>',
			'icon_width' => 65,
			'app_icon' => 'cutlery'
        ));

        \Mobile\Model\AppType::create(array(
            'sort' => 50,
            'name' => 'blog',
            'icon' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 24.1 43.7" style="enable-background:new 0 0 24.1 43.7;" xml:space="preserve">
<style type="text/css">
	.st0{fill:none;stroke:#010101;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
</style>
<g>
	<polyline class="st0" points="18.6,19 21.1,28.9 12.1,42.7 3.1,28.9 5.6,19 	"/>
	<line class="st0" x1="11.7" y1="41.2" x2="11.7" y2="25.2"/>
	<circle class="st0" cx="12.1" cy="22.8" r="2"/>
	<polyline class="st0" points="23.1,1 21.1,18.2 13.1,18.2 11.1,18.2 3.1,18.2 1,1.1 	"/>
</g>
</svg>',
			'icon_width' => 25,
			'app_icon' => 'pen'
        ));

        \Mobile\Model\AppType::create(array(
            'sort' => 60,
            'name' => 'education',
            'icon' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 46 29.4" style="enable-background:new 0 0 46 29.4;" xml:space="preserve">
<style type="text/css">
	.st0{fill:none;stroke:#010101;stroke-width:2;stroke-linejoin:round;stroke-miterlimit:10;}
	.st2{fill:none;stroke:#010101;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
	.st3{fill:#010101}
</style>
<g>
	<path class="st0" d="M10,8L7,21c5,5,25,5,30,0L34,8"/>
	<g>
		<path class="st3" d="M22,2l22,3l-22,5L2,5L22,2 M22,0c-0.1,0-0.2,0-0.3,0l-20,3C0.8,3.2,0,4,0,4.9c0,1,0.6,1.8,1.5,2l20,5
			c0.2,0,0.3,0.1,0.5,0.1c0.1,0,0.3,0,0.4,0l22-5c0.9-0.2,1.6-1.1,1.6-2c0-1-0.8-1.8-1.7-1.9l-22-3C22.2,0,22.1,0,22,0L22,0z"/>
	</g>
	<line class="st2" x1="42.7" y1="10.4" x2="42.7" y2="22.4"/>
	<line class="st2" x1="42.7" y1="26.4" x2="42.7" y2="28.4"/>
</g>
</svg>',
			'icon_width' => 70,
			'app_icon' => 'mortarboard'
        ));

        \Mobile\Model\AppType::create(array(
            'sort' => 70,
            'name' => 'photography',
            'icon' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 48 48" style="enable-background:new 0 0 48 48;" xml:space="preserve">
<style type="text/css">
	.st3{fill:#010101;}
	.st1{fill:none;stroke:#010101;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
</style>
<g>
	<g>
		<path class="st3" d="M24,2c12.1,0,22,9.9,22,22c0,12.1-9.9,22-22,22C11.9,46,2,36.1,2,24C2,11.9,11.9,2,24,2 M24,0
			C10.7,0,0,10.7,0,24c0,13.3,10.7,24,24,24c13.3,0,24-10.7,24-24C48,10.7,37.3,0,24,0L24,0z"/>
	</g>
	<circle class="st1" cx="24" cy="24" r="8"/>
	<line class="st1" x1="23.4" y1="32.5" x2="7.7" y2="40.2"/>
	<line class="st1" x1="30.1" y1="29.6" x2="26.6" y2="46.8"/>
	<line class="st1" x1="32" y1="22.8" x2="43.5" y2="36"/>
	<line class="st1" x1="27.6" y1="16.9" x2="45.4" y2="15.8"/>
	<line class="st1" x1="20" y1="17.1" x2="30.1" y2="2"/>
	<line class="st1" x1="16.2" y1="22.4" x2="11.3" y2="5.5"/>
	<line class="st1" x1="18.1" y1="29.4" x2="1.2" y2="22.8"/>
</g>
</svg>',
			'icon_width' => 45,
			'app_icon' => 'diaphragm'
        ));

        \Mobile\Model\AppType::create(array(
            'sort' => 80,
            'name' => 'other',
            'icon' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 44.9 32.3" style="enable-background:new 0 0 44.9 32.3;" xml:space="preserve">
<style type="text/css">
	.st0{fill:none;stroke:#010101;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
</style>
<g>
	<path class="st0" d="M3.4,31.3c-1.1-6.4,4-20.4,16.8-22.5C33,6.7,39.4,5.7,43.9,1C40.4,12.1,29.1,38.9,9.5,23.7"/>
	<path class="st0" d="M1,17.3c-0.6-22,26.6-13.5,37.4-16"/>
</g>
</svg>',
			'icon_width' => 63,
			'app_icon' => 'leaf'
        ));

    }
}