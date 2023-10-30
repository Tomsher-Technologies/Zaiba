<?php

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        $cats = [];
        $cats['Fire Detection'] = array(
            'Addressable Control Panel',
            'Audible and Visual Alarm',
            'Base',
            'Conventional Control Panel',
            'Detector Testers',
            'Firebell',
            'Flame Detector',
            'Heat Detector',
            'Manual Call Point',
            'Optical Smoke &amp; Heat Detector',
            'Smoke Detector',
            'Other Accessories',
        );

        $cats['Gas Detection'] = array(
            "Fixed Gas Detection Systems",
            "Portable Gas Detectors",
            "Gas Detection Tubes",
            "Wireless Gas Detectors",
            "Accessories",
        );

        $cats['Calibration Gas'] = array(
            "Calibration Gas – Airproducts, Portagas",
            "Carry Case",
        );

        $cats['Airloop System'] = array(
            "Breathing Apparatus",
        );

        $cats['Sounders &amp; Beacons'] = array(
            "Signal Light and Horn",
            "Weatherproof Signal Beacon",
            "Heavy Duty Sounder",
            "Alarm Bell &amp; Beacon",
            "Warning lights",
            "Electronic Sounders",
            "Electric Horn",
            "Ex ploof sounder beacons",
            "Signal Beacon",
            "Limit switches",
            "Air Horn",
        );

        $cats['Automation and Control'] = array(
            "Engine/Steering Controls",
            "Valve Positioners",
            "Inverters",
            "Measuring &amp; Control Instruments",
            "Gauges",
            "Sensors",
        );

        $cats['Light Fixtures'] = array(
            "Bed lamp",
            "Ceiling Lights",
            "Explosion Proof Tubelights",
            "Flood Lights",
            "Navigation lights",
            "Pendant lights",
            "Searchlights",
            "Spot Lights",
            "Electric Connectors",
        );

        $cats['Personal Protective Equipment'] = array();
        $cats['Life Safety Products'] = array();
        $cats['Temperature/Pressure Calibrators'] = array();
        $cats['Alcohol Test Kits'] = array();
        $cats['Battery Chargers'] = array();
        $cats['Others'] = array();
        $cats['Compressor'] = array();
        $cats['Synchroscope'] = array();
        $cats['Conventional Governors'] = array();
        $cats['Generator Controller'] = array();
        $cats['Windsock Frame'] = array();
        $cats['IMO Safety Signs'] = array();

        $cats['Ventilation'] = array('Fan');
        $cats['Audio Visual'] = array('Cameras', 'Video Recorders');

        $cats['Communication System'] = array(
            "PA/GA Systems",
            "Sound Reception System",
            "Telephone Systems , Speakers and Microphones",
            "VHF Radio",
        );

        $cats['UTI Level Gauges'] = array(
            "Hermetic",
            "MMC",
        );

        $cats['Electrical'] = array(
            "Voltage Regulators",
            "Actuator",
            "Hot Plates",
            "Potentiometer",
            "Power Supply",
            "Relay",
            "Switches",
            "Plugs and Sockets",
            "Electrical Protection and Control",
            "MCCB",
            "Marine Cable",
            "Electric Heater",
        );

        $cats['Field Measuring Instruments'] = array(
            "Digital Multimeter",
            "Industrial Multimeter",
            "Insulation Resistance Tester",
            "Analog Insulation Tester",
            "Digital Insulation Tester",
            "Infrared Thermometer",
            "Infrared Laser Thermometer",
            "Automotive Multimeter",
            "Phase Rotation Indicator",
            "Clamp Meter",
            "Analog Earth Tester",
            "Digital Earth Tester",
            "Voltage Detector",
            "Hi-Tester",
            "Tachometer",
        );

        foreach ($cats as $key => $cat) {
            $parent = Category::create([
                'parent_id' => 0,
                'level' => 0,
                'name' => $key,
                'order_level' => 1,
                'featured' => 0,
                'slug' => Str::slug($key),
            ]);

            if (count($cat)) {
                foreach ($cat as $key => $c) {
                    $child = Category::create([
                        'parent_id' => $parent->id,
                        'level' => 1,
                        'name' => $c,
                        'order_level' => 1,
                        'featured' => 0,
                        'slug' => Str::slug($c),
                    ]);
                }
            }
        }
    }
}
