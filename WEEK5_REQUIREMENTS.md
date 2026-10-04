# Week 5 Requirements: Usability Study

Course assignment (DEV 640, Week 5): each team is paired with another team. Members of the paired team act as testers for the other team's project, and the team that was tested then writes up what happened. This page explains what is needed from everyone.

## What the assignment asks for

1. **A usability script.** A list of tasks that tell testers what to accomplish, without telling them where to click or how to do it.
2. **A recorded session.** Members of the paired team complete the tasks while the screen and voice are recorded.
3. **A summary.** Written after reviewing the recording. It covers what worked well, where users had difficulty, the feedback and suggestions they gave, and what could be improved in the project.

The script and summary together should be **2 to 3 pages**. This is a team assignment, but only one member of each team submits.

## Who does what

| Role | What they do | What they produce |
|---|---|---|
| Facilitator | Reads the introduction and each task aloud, gives no hints, asks the questions | A consistent session for every tester |
| Note-taker | Records time, result, ease rating, hesitations and word-for-word quotes for every task | Completed observation notes |
| Tester (paired team) | Completes the tasks while thinking out loud, rates each task, answers the closing questions | Honest first impressions |
| Submitter (one per team) | Uploads the final document | The submission |

## What a tester does (about 20 minutes)

- Agree to be recorded (screen and voice) before the session starts.
- Complete a short list of tasks read aloud. Please think out loud: say what you are looking at, what you expect to happen and what surprises you.
- Expect no hints. If you are stuck for about 2 minutes, you will be asked what you would try next. If you are still stuck, the task is marked as failed and you move on. That is useful information, not a mistake.
- Rate each task after you finish it.
- Answer five closing questions.

Please do not read the task list in advance, including `USABILITY_STUDY.md` in this repository, so that your first impressions are genuine.

## Questions testers will be asked

After every task:

> On a scale of 1 (very difficult) to 7 (very easy), how easy was that task?

At the end:

1. What was the easiest part of the site? What was the most frustrating?
2. Was there any time you were not sure what had happened after you clicked something?
3. Were any words or button labels confusing?
4. On a scale of 1 to 5, how likely would you be to use this at work? Why?
5. If you could change one thing, what would it be?

## What each team's document must contain

- **Header:** team, paired team, date, facilitator, note-taker and the recording file name or link.
- **Part 1, Test script:** setup checklist, the introduction read aloud (including the request to record), the tasks with success criteria, and the questions above.
- **Part 2, Observation sheet:** for each task, how many testers completed it, average time, average ease rating, and errors, hesitations or quotes.
- **Part 3, Summary of findings:**
  - What works, with evidence (task number, recording timestamp, quote).
  - Where testers had difficulty, most serious first, with severity (High, Medium or Low) and a proposed fix.
  - Feedback and suggestions from the testers.
  - A few participant quotes.
  - Next steps: what the team will change first, and who will do it.
- **Length:** script and summary combined, 2 to 3 pages.

## Session logistics

- Allow about 20 minutes per tester. Three or four testers per team is ideal.
- **In person:** put both laptops on the same Wi-Fi. The facilitator runs `./launch_website.sh --network` and gives the tester the "Network" address it prints.
- **Remote:** meet on Zoom. The tester shares their screen and the facilitator opens the site for them. Record the Zoom session.
- Testers need a laptop, a browser and an internet connection.
- Get a spoken "yes" to recording at the start, and say that the recording is used only by the team for this class.
- Stop the server (Ctrl+C) when the session is over.

## Checklist

- [ ] Paired team contacted and sessions scheduled
- [ ] Script and note-taker sheet ready
- [ ] Demo accounts created (`demo_alex` and `demo_sam`)
- [ ] Each session recorded, with consent
- [ ] Observation sheet filled in
- [ ] Summary written from the recordings
- [ ] Document is 2 to 3 pages
- [ ] One team member submits
