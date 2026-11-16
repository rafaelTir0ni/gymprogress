<?php
declare(strict_types=1);

final class Conquista
{
    public static function definitions(): array
    {
        return [
            ['recorde_pessoal','Recorde pessoal','Você superou sua maior carga anterior.'],
            ['recorde_semanal','Recorde semanal','Você superou uma carga anterior na mesma semana.'],
            ['frequencia','Frequência semanal','Você atingiu um marco de treinos na semana.'],
            ['marco_sessoes','Marco de sessões','Você alcançou uma quantidade de treinos finalizados.'],
        ];
    }

    public static function ensureCatalog(): array
    {
        $st=db()->prepare('INSERT INTO conquistas (codigo,nome,descricao,tipo) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE codigo=VALUES(codigo)');
        foreach(self::definitions() as [$type,$name,$desc])$st->execute([$type,$name,$desc,$type]);
        $rows=db()->query('SELECT id,codigo FROM conquistas WHERE ativo=1')->fetchAll();
        $ids=[];foreach($rows as $r)$ids[$r['codigo']]=(int)$r['id'];return $ids;
    }
}
