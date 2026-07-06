<p>Здравствуйте!</p>
<p>Ваш полис № {{ $policy->number }} оформлен.</p>
<p>Премия: {{ number_format($policy->premium,2,',',' ') }} ₽</p>
<p>Период: {{ optional($policy->start_date)->format('d.m.Y') }} – {{ optional($policy->end_date)->format('d.m.Y') }}</p>
<p>Полис во вложении.</p>
<p>СК Турикум</p>
