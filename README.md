# [Cora] - Boleto bancário para WHMCS

Este plugin para [WHMCS] visa implatar a forma pagamento via boleto bancário registrado da [Cora].

## Recursos
- Checkout e emissão boleto bancário
- Retorno automático
- Segunda via de boleto

## Instalação
- Copie os arquivos que estão na pasta "modules/gateways" para a respectiva pasta em seu gerenciador [WHMCS].
- Na administração, acesse o menu Configuration -> Payment Gateways.
- Na lista: Aparecerá o seguinte gateway de pagamento:
"Cora - Boleto"
- Clique em "Activate".
- Informe sua chave de licença e clique em "Save Changes".
- Clique em "Autorizar App" e informe os dados da sua conta Cora.
- Agora copie os arquivos que estão na pasta "modules/gateways/callback" para a respectiva pasta em seu gerenciador

## Retorno automático
- Retorno automatico já esta ativo por padrão
- A cada alteração do status da fatura enviarem um POST para o endereço /modules/gateways/callback/coraboleto.php

## Desenvolvimento
- [FoxIT]
- [Divox] 

## Contribuição
- [Miquéias Francisco]
- Renato Caetano

## Licença

MIT

**Software livre, claro que sim!**

   [Miquéias Francisco]: <https://miqueiasfrancisco.com.br>
   [FoxIT]: <https://foxit.com.br>
   [Divox]: <https://www.divox.com.br>
   [Cora]: <https://www.cora.com.br>
   [WHMCS]: <https://www.whmcs.com/>
