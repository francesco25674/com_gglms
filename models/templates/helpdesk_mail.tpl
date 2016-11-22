{* Smarty *}
<html>
	<head>
	
	</head>
	<body>
		<h1>Richiesta Assistenza</h1>
                
                <h2>Dettali richiesta:</h2>
                    
                        <p> 
                         Nome: {$data.fromname} <br />
                         Email: {$data.frommail}  <br />
                         Messaggio : <br />
                         {$data.testo} </p>
                         
                        <hr>
                        <p>
                        ID Utente: {$data.idutente} <br />
                        Username: {$data.username} <br />
                        Società: {$data.societa} <br />

                        </p>
		
	</body>
</html>
