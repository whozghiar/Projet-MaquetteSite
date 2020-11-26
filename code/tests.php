<?php

    function isValid($date, $format = 'd/m/Y'){

        $dt = DateTime::createFromFormat($format, $date);
        return $dt && $dt->format($format) === $date;

    }
    function calculAge($date)
    {
        if (strlen($date) != 10) {
            return false;
        }


        if (isValid($date,$format = 'd/m/Y') == False){
            return false;
        }

        $am = explode('/', $date);
        $an = explode('/', date('d/m/Y'));

        if ($am[2]>$an[2]){
            return false;
        }


        
        if(($am[1] < $an[1]) || (($am[1] == $an[1]) && ($am[0] <= $an[0]))) {
            
            return $an[2] - $am[2];
        }
        
        return $an[2] - $am[2] - 1; 

    }




       

    function TestCalculAge()
    {
        $test_tab = [
        ["10/10/2000",20],
        ["27/02/2001",19],
        ["10/20/2000",FALSE],
        ["10/12/2050",FALSE],
        ["29/02/2001",FALSE], 
        ["30/02/2000",FALSE], 
        ["29/02/200",FALSE],
        ["29/02/2001/",FALSE]
        ];

        $ok = TRUE;

        foreach($test_tab as $testval)
        {
            
            if(CalculAge($testval[0]) != $testval[1])
            {
                $ok = FALSE;
                
            }
    
        }

        return $ok;
    }

    if (TestCalculAge())
    {
        echo"\nTest Vrai";
    }
    else
    {
        echo"\nTest Faux";
    }

?>