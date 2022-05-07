<template>
	<!-- Modal -->
	<div
		class="modal fade"
		id="editModal"
		tabindex="-1"
		aria-labelledby="editModalLabel"
		aria-hidden="true"
	>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editModalLabel">Edit Task:</h5>
					<button
						type="button"
						class="btn"
						data-bs-dismiss="modal"
						aria-label="Close"
					>
						<em class="bi bi-x-lg"></em>
					</button>
				</div>
				<div class="modal-body">
					<textarea
						class="form-control"
						rows="3"
						v-model="taskInput.task_description"
					/>
				</div>
				<div class="modal-footer">
					<button
						type="button"
						class="btn btn-primary"
						:disabled="isDisabled"
						@click="
							$emit(
								'update',
								`${taskInput.id}`,
								`${taskInput.task_description}`,
								`${taskInput.task_status}`
							)
						"
						data-bs-dismiss="modal"
					>
						Save changes
					</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	props: ["task"],
	data() {
		return {
			taskInput: { ...this.task },
		};
	},
	computed: {
		isDisabled() {
			return (
				this.taskInput.task_description === null ||
				this.taskInput.task_description.match(/^\s*$/) !== null
			);
		},
	},
	methods: {
		resetField() {
			this.taskInput.task_description = this.task.task_description;
			console.log("Field is reset.");
		},
	},
	mounted() {
		//Add an event to Modal that's called when modal is hidden
		var editModalElement = document.getElementById("editModal");
		editModalElement.addEventListener("hidden.bs.modal", () => {
			this.resetField();
		});
	},
	watch: {
		task: function (newVal) {
			this.taskInput = { ...newVal };
		},
	},
};
</script>
<style></style>
