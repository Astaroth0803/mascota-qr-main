                <x-dashboard.stat-card
                    title="Registros de Vacunación"
                    :value="$statistics['pending_vaccinations'] ?? 0"
                    icon="vaccine"
                    class="text-blue-600"
                />
