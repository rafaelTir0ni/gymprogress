<?php
declare(strict_types=1);

final class DashboardController
{
    public function index(): void
    {
        requireLogin();
        $uid = currentUserId();
        $st = db()->prepare('SELECT timezone FROM usuarios WHERE id = ?');
        $st->execute([$uid]);
        $timezoneName = (string) ($st->fetchColumn() ?: 'America/Sao_Paulo');
        try { $timezone = new DateTimeZone($timezoneName); }
        catch (Exception $exception) { $timezone = new DateTimeZone('America/Sao_Paulo'); }

        $today = new DateTimeImmutable('now', $timezone);
        $weekStart = $today->modify('monday this week')->setTime(0, 0);
        $monthStart = $today->modify('first day of this month')->setTime(0, 0);
        $utc = new DateTimeZone('UTC');
        $weekUtc = $weekStart->setTimezone($utc)->format('Y-m-d H:i:s');
        $monthUtc = $monthStart->setTimezone($utc)->format('Y-m-d H:i:s');

        // Uma consulta para os dois indicadores; somente sessões finalizadas do proprietário.
        $st = db()->prepare("SELECT
            COALESCE(SUM(finalizada_em >= ?), 0) AS semana,
            COALESCE(SUM(finalizada_em >= ?), 0) AS mes
            FROM sessoes WHERE usuario_id = ? AND status = 'finalizada'");
        $st->execute([$weekUtc, $monthUtc, $uid]);
        $counts = $st->fetch() ?: ['semana' => 0, 'mes' => 0];

        $st = db()->prepare("SELECT s.id, s.nome_ficha_snapshot, s.iniciada_em,
                s.finalizada_em, s.duracao_segundos,
                (SELECT COUNT(*) FROM series_realizadas sr
                 JOIN sessao_exercicios se ON se.id = sr.sessao_exercicio_id
                 WHERE se.sessao_id = s.id AND sr.concluida = 1) AS series_concluidas
            FROM sessoes s
            WHERE s.usuario_id = ? AND s.status = 'finalizada'
            ORDER BY s.finalizada_em DESC, s.id DESC LIMIT 1");
        $st->execute([$uid]);
        $latest = $st->fetch() ?: null;
        $active = Sessao::active($uid);

        $displayDate = static function (?string $date) use ($timezone): string {
            if (!$date) return '—';
            try {
                return (new DateTimeImmutable($date, new DateTimeZone('UTC')))
                    ->setTimezone($timezone)->format('d/m/Y H:i');
            } catch (Exception $exception) { return '—'; }
        };
        $notice = takeFlash();
        require dirname(__DIR__) . '/views/dashboard/index.php';
    }
}
