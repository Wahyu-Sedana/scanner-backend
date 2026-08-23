<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\Passcode;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('passcodes:assign-remaining {--dry-run : Preview the codes without saving anything}')]
#[Description('Generate a COCOMART#####1002 passcode for every employee that does not have one yet, and assign it.')]
class AssignPasscodesToRemainingEmployees extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $nextNumber = $this->nextSequenceNumber();

        $employees = Employee::whereNull('passcode_id')->orderBy('id')->get();

        if ($employees->isEmpty()) {
            $this->info('Semua employee sudah punya passcode.');

            return self::SUCCESS;
        }

        $rows = $employees->map(function (Employee $employee) use (&$nextNumber) {
            $code = sprintf('COCOMART%d1002', $nextNumber++);

            return [$employee, $code];
        });

        $this->table(
            ['Employee', 'Passcode baru'],
            $rows->map(fn ($row) => [$row[0]->name, $row[1]]),
        );

        if ($this->option('dry-run')) {
            $this->comment('Dry run — tidak ada data yang disimpan.');

            return self::SUCCESS;
        }

        if (! $this->confirm(sprintf('Buat %d passcode baru dan assign ke employee di atas?', $rows->count()))) {
            $this->comment('Dibatalkan.');

            return self::SUCCESS;
        }

        DB::transaction(function () use ($rows) {
            foreach ($rows as [$employee, $code]) {
                $passcode = Passcode::create(['code' => $code, 'is_active' => true]);
                $employee->update(['passcode_id' => $passcode->id]);
            }
        });

        $this->info(sprintf('%d passcode berhasil dibuat dan di-assign.', $rows->count()));

        return self::SUCCESS;
    }

    private function nextSequenceNumber(): int
    {
        $highest = Passcode::query()
            ->where('code', 'like', 'COCOMART%1002')
            ->pluck('code')
            ->map(function (string $code) {
                preg_match('/^COCOMART(\d+)1002$/', $code, $matches);

                return isset($matches[1]) ? (int) $matches[1] : null;
            })
            ->filter()
            ->max();

        return ($highest ?? 0) + 1;
    }
}
