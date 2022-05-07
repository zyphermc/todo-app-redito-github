<template>
	<div class="mx-4 pt-3">
		<div class="row justify-content-center">
			<div class="my-1">
				<input
					type="text"
					class="form-control"
					placeholder="Enter task"
					v-model="taskInput.task_description"
				/>
			</div>
			<div class="mx-2 my-1">
				<div class="dropdown">
					<button
						class="btn btn-secondary dropdown-toggle"
						type="button"
						data-bs-toggle="dropdown"
					>
						{{ taskInput.task_status }}
					</button>
					<ul class="dropdown-menu">
						<li>
							<a class="dropdown-item" @click="selectInputStatus('Pending')"
								>Pending</a
							>
						</li>
						<li>
							<a class="dropdown-item" @click="selectInputStatus('In Progress')"
								>In Progress</a
							>
						</li>
						<li>
							<a class="dropdown-item" @click="selectInputStatus('Done')"
								>Done</a
							>
						</li>
					</ul>
				</div>
			</div>
			<div class="my-1">
				<button
					type="button"
					class="btn btn-primary"
					@click="addToList(taskInput)"
				>
					Submit
				</button>
			</div>
		</div>
	</div>
	<hr class="border-primary mb-2 w-50" />
	<div class="d-flex flex-column align-items-center">
		<h1>To do list:</h1>

		<div class="card mb-2 mx-5" v-show="todoList.length > 0">
			<ul class="list-group list-group-flush">
				<li
					class="list-group-item"
					v-for="(item, index) in todoList"
					:key="index"
				>
					<div class="d-flex justify-content-between">
						<div class="text-wrap mr-5">
							{{ item.task_description }}
						</div>

						<div class="row justify-content-end">
							<div class="dropdown mx-1 mb-2">
								<button
									class="btn dropdown-toggle"
									:class="getButtonClass(item.task_status)"
									type="button"
									data-bs-toggle="dropdown"
									@click="selectTask(index)"
								>
									{{ item.task_status }}
								</button>
								<ul class="dropdown-menu">
									<li>
										<a
											class="dropdown-item"
											@click="
												updateTask(item.id, item.task_description, 'Pending')
											"
											>Pending</a
										>
									</li>
									<li>
										<a
											class="dropdown-item"
											@click="
												updateTask(
													item.id,
													item.task_description,
													'In Progress'
												)
											"
											>In Progress</a
										>
									</li>
									<li>
										<a
											class="dropdown-item"
											@click="
												updateTask(item.id, item.task_description, 'Done')
											"
											>Done</a
										>
									</li>
								</ul>
							</div>
							<div class="mx-1">
								<button
									type="button"
									class="btn btn-primary"
									data-bs-toggle="modal"
									data-bs-target="#editModal"
									@click="selectTask(index)"
								>
									<em class="bi bi-pencil-square"></em>
								</button>
							</div>
							<div class="mx-1">
								<button
									class="btn btn-danger"
									@click="deleteTask(item.id, index)"
								>
									<em class="bi bi-trash"></em>
								</button>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>

	<!----Modals----->
	<EditModal
		:task="{ ...todoList[selectedTaskIndex] }"
		v-if="todoList[selectedTaskIndex]"
		@update="updateTask"
	/>
</template>
<script>
import { onMounted, ref } from "vue";
import axios from "axios";
import EditModal from "@/components/EditModal.vue";
import { useToast } from "vue-toastification";

export default {
	name: "HomePage",
	components: { EditModal },
	setup() {
		//Localhost port hosting PHP server
		//Setup server on root folder -> cmd "php -S localhost:port"
		const PHP_SERVER_URL = "http://localhost:3000";

		const toast = useToast();
		const todoList = ref([]);
		const taskInput = ref({
			task_description: "",
			task_status: "Select task status",
		});
		const selectedTaskIndex = ref(0);

		const fetchData = () => {
			axios
				.get(`${PHP_SERVER_URL}/db_tasks.php?action=read`)
				.then(function (response) {
					todoList.value = response.data.tasks;
				})
				.catch((error) => {
					alert(error);
				});
		};

		const addToList = (input) => {
			if (
				isEmptyOrSpaces(input.task_description) ||
				input.task_status == "Select task status"
			) {
				toast.error("Invalid input!");
				return;
			}

			axios
				.post(`${PHP_SERVER_URL}/db_tasks.php?action=create`, {
					task_description: input.task_description,
					task_status: input.task_status,
				})
				.then(() => {
					fetchData();
					toast.success("Task successfully added!");
					taskInput.value.task_description = "";
				})
				.catch((error) => {
					alert(error);
				});
		};

		const updateTask = (id, description, status) => {
			axios
				.post(`${PHP_SERVER_URL}/db_tasks.php?action=update`, {
					id: id,
					task_description: description,
					task_status: status,
				})
				.then(function () {
					todoList.value[selectedTaskIndex.value].task_description =
						description;
					todoList.value[selectedTaskIndex.value].task_status = status;
					toast.success("Task successfully updated!");
				})
				.catch(function (error) {
					alert(error);
				});
		};

		const deleteTask = (id, index) => {
			axios
				.post(`${PHP_SERVER_URL}/db_tasks.php?action=delete`, {
					id: id,
				})
				.then(() => {
					todoList.value.splice(index, 1);
					toast.success("Task successfully deleted!");
				})
				.catch((error) => {
					alert(error);
				});
		};

		//For identifying what data to give to modal
		const selectTask = (index) => {
			selectedTaskIndex.value = index;
		};

		//For storing what status the input is assigned
		const selectInputStatus = (status) => {
			taskInput.value.task_status = status;
		};

		const isEmptyOrSpaces = (string) => {
			return string === null || string.match(/^\s*$/) !== null;
		};

		//For dynamic coloring of task status
		const getButtonClass = (status) => {
			if (status == "Pending") return "btn-warning";
			if (status == "In Progress") return "btn-info";
			if (status == "Done") return "btn-success";
			return "btn-secondary";
		};

		onMounted(() => {
			fetchData();
		});

		return {
			taskInput,
			todoList,
			selectedTaskIndex,
			addToList,
			deleteTask,
			updateTask,
			selectTask,
			selectInputStatus,
			getButtonClass,
		};
	},
};
</script>
