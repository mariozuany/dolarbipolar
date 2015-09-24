# Dólar Bipolar

Criado por André Fantin, o perfil @DolarBipolar, faz o acompanhamento da variação da cotação do Dólar durante o dia. As postagens começaram sendo postadas manualmente, mas hoje possui uma programação que faz atualizações automáticas a cada meia hora. Criei um Bot que utiliza o serviço de cotação da moeda fornecida pelo UOL e realiza postagens automáticas via API do Twitter.

O BOT do DólarBipolar funciona de forma bem simples. Um cronjob roda a cada meia hora o script PHP, que captura a cotação da moeda no UOL, compara a última cotação com o valor corrente e realiza uma pequena formatação. Além disso o script insere o horário da cotação, já que o Twitter não permite postar mensagens iguais numa pequena variação de tempo. Finalmente ele se conecta com a API do Twitter utilizando a biblioteca Twitteroauth do Abraham Williams e publica o post.

### Requisitos 

-  Twitteroauth, de Abraham Williams (https://github.com/abraham/twitteroauth).