<?php

class Ticker extends Colors
{
    
    public function __construct()
    {
        $colors = new Colors();
        while (true) {
            $this->get_data(array(
                'GOOG',
                'AAPL',
                'MSFT'
            ), $colors);
        }
    }
    
    public function get_data($symbols, $colors)
    {
        sleep(5);
        echo "\n\n\n" . date('Y m d H:i:s') . "\n";
        sort($symbols);
        $return = '';
        
        $opts = array(
            'http' => array(
                'method' => "GET",
                'proxy' => 'cache.llgc.org.uk:80',
                'request_fulluri' => true
            )
        );
        
        $context = stream_context_create($opts);
        
        foreach ($symbols as $symbol) {
            $data = file_get_contents("http://quote.yahoo.com/d/quotes.csv?s=$symbol&f=sl1d1t1c1ohgv&e=.csv", false, $context);
            
            $values    = explode(",", $data);
            $lasttrade = $values[1];
            $change    = $values[4];
            
            $return .= "$symbol ";
            $return .= "$lasttrade ";
            if ($change < 0)
                $return .= $colors->getColoredString($change, "red", "black");
            else
                $return .= $colors->getColoredString($change, null, "cyan");
            
            $return .= "\n";
        }
        echo "$return\n";
    }
}


class Colors
{
    private $foreground_colors = array();
    private $background_colors = array();
    
    public function __construct()
    {
        // Set up shell colors
        $this->foreground_colors['black']        = '0;30';
        $this->foreground_colors['dark_gray']    = '1;30';
        $this->foreground_colors['blue']         = '0;34';
        $this->foreground_colors['light_blue']   = '1;34';
        $this->foreground_colors['green']        = '0;32';
        $this->foreground_colors['light_green']  = '1;32';
        $this->foreground_colors['cyan']         = '0;36';
        $this->foreground_colors['light_cyan']   = '1;36';
        $this->foreground_colors['red']          = '0;31';
        $this->foreground_colors['light_red']    = '1;31';
        $this->foreground_colors['purple']       = '0;35';
        $this->foreground_colors['light_purple'] = '1;35';
        $this->foreground_colors['brown']        = '0;33';
        $this->foreground_colors['yellow']       = '1;33';
        $this->foreground_colors['light_gray']   = '0;37';
        $this->foreground_colors['white']        = '1;37';
        
        $this->background_colors['black']      = '40';
        $this->background_colors['red']        = '41';
        $this->background_colors['green']      = '42';
        $this->background_colors['yellow']     = '43';
        $this->background_colors['blue']       = '44';
        $this->background_colors['magenta']    = '45';
        $this->background_colors['cyan']       = '46';
        $this->background_colors['light_gray'] = '47';
    }
    
    // Returns colored string
    public function getColoredString($string, $foreground_color = null, $background_color = null)
    {
        $colored_string = "";
        
        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }
        
        // Add string and end coloring
        $colored_string .= $string . "\033[0m";
        
        return $colored_string;
    }
    
    // Returns all foreground color names
    public function getForegroundColors()
    {
        return array_keys($this->foreground_colors);
    }
    
    // Returns all background color names
    public function getBackgroundColors()
    {
        return array_keys($this->background_colors);
    }
}


new Ticker();
