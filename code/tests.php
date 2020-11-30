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




       

    function testCalculAge()
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
            
            if(calculAge($testval[0]) != $testval[1])
            {
                $ok = FALSE;
                
            }
    
        }

        return $ok;
    }

    if (testCalculAge())
    {
        echo"\nTest calculAge Vrai \n";
    }
    else
    {
        echo"\nTest calculAge Faux \n ";
    }


    function verifMail($mail){

        $valide = False;

        if (filter_var($mail,FILTER_VALIDATE_EMAIL)){
            $valide = True;
        }
        return $valide;
    }

    function testVerifMail(){

        $test_tab = [
            ["theo@hotmail",FALSE],
            ["hugo@hotmail.fr",True],
            ["jordanoutlook.com",FALSE],
            ["wassim@",FALSE],
            ["bilojasa@hotmail.com",True], 
            ["test",False], 
            ["e@gmail.fr",True],
            ["e@f.de",True]
            ];
    
            $ok = TRUE;
    
            foreach($test_tab as $testval)
            {
                
                if(verifMail($testval[0]) != $testval[1])
                {
                    $ok = FALSE;
                    
                }
        
            }
    
            return $ok;
    }


    if (testVerifMail())
    {
        echo"\nTest Mail Vrai\n";
    }
    else
    {
        echo"\nTest Mail Faux\n";
    }


    function verifCarteBancaire($cb){
        $valide = True; 
        if (!is_numeric($cb)){
            $valide = False;
        }

        if (strlen($cb) != 16){
            $valide = False;
        } 
        return $valide;

    }

    function testVerifCarteBancaire(){

        $test_tab = [
            ["0306020504010306",True],
            ["11111",False],
            ["0986020444412306",True],
            ["wassim@",False],
            ["abcdefghijkljkal",False], 
            ["0634472514",False], 
            ["698*9856éd",False],
            ["",False]
            ];
    
            $ok = TRUE;
    
            foreach($test_tab as $testval)
            {
                
                if(verifCarteBancaire($testval[0]) != $testval[1])
                {
                    $ok = FALSE;
                    
                }
        
            }
    
            return $ok;
    }


    if (testVerifCarteBancaire())
    {
        echo"\nTest Carte Bancaire Vrai\n";
    }
    else
    {
        echo"\nTest Carte Bancaire Faux\n";
    }

    
?>