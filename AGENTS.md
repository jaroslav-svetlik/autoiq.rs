# AutoIQ editorial publication requirements

Read `docs/BLOG_EDITORIAL_PLAYBOOK.md`, including the owner revision of 2026-10-02, and the dated manifests in `storage/app/editorial/private` before choosing topics. Read `/Users/jaroslavsvetlik/Documents/Serveri/automatizacije-produkcija.md`, the current migration report and `docs/DEPLOYMENT.md` before any production operation. Current server policy overrides historical runbooks.

## Publication gate after the October 6 repair

- A retry never creates a second batch: inspect production first; the Europe/Belgrade daily limit remains five. The five October 6 posts are existing IDs 651–655, repaired by v0.1.201, not candidates for republishing.
- Store reviewed daily data separately, as in `database/seeders/data/october-sixth-2026.php`. Every new batch loader and every scoped publication/repair must invoke `App\Support\EditorialBatchValidator::validate()` on all five final articles. Extend focused tests for the new batch; do not remove or weaken the gate to publish a short draft.
- Write 700–1100 substantive body words for comparisons/model guides and 500–800 for inspection/market articles, with 4–6 tailored sections. Separate headings and paragraphs with a blank line. Minimum length is a structural check, not permission to pad prose.
- Review exact generations, engines and transmissions against original sources; preserve a source/intent/specific-facts manifest before writing. Every guide needs model-specific facts and a reasoned recommendation, not the same checklist with another model name.
- Assert that `professionalizeEditorialVoice()` leaves every final article unchanged. Ordinary persona words can trigger destructive legacy replacements. Do not silently publish its generic substitutes.
- Test the rendered route and assert the presence of **every** content block, including paragraphs after headings. Review all final articles editorially and the actual live render, not merely HTTP status or test totals.
- Run `bash scripts/validate-editorial-batch.sh` before committing/tagging/deploying. Preserve unrelated dirty files and hardened production dependencies. Deploy only reviewed source files through the production copy's `scripts/deploy-prod.py`.
- Publication uses a reviewed transaction scoped to the exact batch and a small private affected-row rollback journal. Never run the historical seeder in production. Preserve publication times, URLs and covers during repairs. Verify all five live article bodies, all five WebP URLs and sitemap after the cache rebuild.

## Scheduled chats

Each scheduled execution runs in its own scheduler-created chat. Do not create another chat or message the previous run. New/changed automations are local standalone cron jobs, not thread-bound heartbeats; preserve schedule/status/model/notifications unless explicitly instructed otherwise. Persist verified progress in existing manifests and automation memory.

## Daily execution after October 10

Read `docs/EDITORIAL_DAILY_WORKFLOW.md`. Missing reviewed daily data or images is work to perform, not a blocker: research, write, test, generate/review covers, release, deploy, publish and verify in this same run. Do not finish after a research manifest or local draft. Only a verified external impediment that cannot safely be resolved in scope permits an incomplete run; report the exact failing command/state and retain resumable progress.

Use dated `database/seeders/data/YYYY-MM-DD.php` with `ReviewedEditorialBatch::load()` and `editorial:publish YYYY-MM-DD`, never append new publication batches to the frozen historical seeder. Keep new/changed tests ignored under `tests/Feature/Local/`; extend and execute them locally for each batch and never stage, commit or push tests. This owner policy overrides older playbook instructions to commit tests.

Success requires exactly five production rows for the actual Europe/Belgrade date and all five publicly visible complete articles, five real WebP covers and sitemap entries. Run `php scripts/verify-editorial-batch.php YYYY-MM-DD` after production media optimization/cache rebuild, and visually inspect the live pages. A previous complete date is not republished; an exact partial batch resumes missing rows only. Never invent or backdate missed batches.
