# payment-test

Postman Collection 
https://api.postman.com/collections/550616-60d46d6d-ab13-4c6e-abe3-138489f07006?access_key=PMAT-01HGH8777RX99GT7VX8TH8Z5NG


**Create payment method** 

[POST] /v1/payment-method

Payload {
            "method_id" : "D",
            "name": "Cartão de Débito",
            "tax_percentage" : "0.03"
        }
        
       
       
**Create new customer and account**  

[POST] /v1/operation/new-customer

Payload {
            "customer" : {
                "name" : "joao",
                "vat_number" : "123456789000"
            },
            "account" : {
                "balance" : 100
            }
        }
        
    
    
    

       
       
**Create new account**  

[POST] /v1/account/create

Payload {
            "vat_number" : "123456789000",
            "balance" : 100
        }


       
       
**Create credit operation**  

[POST] /v1/operation/credit

Payload {
            "account_number" : "100239183",
            "amount" : "20"
        }
        
        
        
       
**Create debit operation**  

[POST] /v1/operation/debit

Payload {
            "account_number" : "100239183",
            "payment_method" : "P",
            "amount" : "20"
        }
        


**Setup**

É necessário ter o Docker instalado.

execute os comandos  
 - docker-compose up -d //para subir o container
 - docker exec -t payment-test_web_1 ash //para acessar o container
 - composer install //para instalar as dependencias
 - php artisan db:create //para criar o banco
 - php artisan migrate //para criar a tabelas do banco