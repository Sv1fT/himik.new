<?php

namespace App\Http\Controllers;

use App\Reklams_XML;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function bills_xml_in()
    {
        Reklams_XML::orderBy('id','asc')->delete();

        if ($handle = opendir('/var/www/opt-himik.ru/public/upload/exports')) {
            #echo "Дескриптор каталога: $handle\n";


            /* Именно этот способ чтения элементов каталога является правильным. */
            while (false !== ($file = readdir($handle))) {
                if (preg_match("|.xml$|", $file)) {

                    $file_name = $file;
                    $xml = simplexml_load_file("/var/www/opt-himik.ru/public/upload/exports/$file_name");
                    foreach ($xml as $item) {

                        $bills = new Reklams_XML();
                        $bills->name = $item->name;
                        $bills->email = $item->email;
                        $bills->number = $item->number;
                        $bills->city = $item->city;
                        $bills->about = $item->about;
                        $bills->save();

                    }
                    unlink("/var/www/opt-himik.ru/public/upload/exports/$file_name");
                    $count = count($xml);
                }
            }
            $echo = Reklams_XML::all();
            print_r('Данные загружены!');
            $current_time = Carbon::now();




            closedir($handle);
        }
    }
}
